<?php

declare(strict_types=1);

namespace App;

use App\Middleware\JwtMiddleware;
use Authentication\Identifier\IdentifierInterface;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Authentication\AuthenticationService;
use Psr\Http\Message\ServerRequestInterface;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    public function bootstrap(): void
    {
        parent::bootstrap();

        if (PHP_SAPI !== 'cli') {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        // Đăng ký plugin Authentication
        $this->addPlugin('Authentication');
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))
            ->add(new RoutingMiddleware($this))
            ->add(new BodyParserMiddleware())
            // ->add(new CsrfProtectionMiddleware([
            //     'httponly' => true,
            // ]))
            // ✨ Middleware JWT
            ->add(new JwtMiddleware())
            // ✨ Middleware xác thực
            ->add(new AuthenticationMiddleware($this));
        return $middlewareQueue;
    }

    /**
     * Cấu hình xác thực người dùng
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService([
            'unauthenticatedRedirect' => '/users/login',
            'queryParam' => 'redirect',
        ]);

        $fields = [
            'username' => 'email',
            'password' => 'password',
        ];

        // ⚠️ Identifier dùng cho login với mật khẩu
        $service->loadIdentifier('Authentication.Password', [
            'fields' => $fields,
            'resolver' => [
                'className' => 'Authentication.Orm',
                'userModel' => 'Users',
            ],
        ]);

        // ⚠️ Identifier dùng để lấy user từ JWT (sử dụng field `sub` từ token)
        $service->loadIdentifier('Authentication.JwtSubject', [
            'tokenField' => 'sub',
            'dataField' => 'sub',
            'resolver' => [
                'className' => 'Authentication.Orm',
                'userModel' => 'Users',
            ],
        ]);

        // ⚠️ Authenticator cho đăng nhập bằng Form (Username + Password)
        $service->loadAuthenticator('Authentication.Form', [
            'fields' => $fields,
            'loginUrl' => '/users/login', // Hoặc cho api-login
        ]);

        // Authenticator cho Session (thường dùng sau khi đăng nhập thành công)
        $service->loadAuthenticator('Authentication.Session');

        // Đảm bảo rằng phần này không cần Middleware thêm nữa
        return $service;
    }


    public function services(ContainerInterface $container): void {}
}

<?php

namespace App\Middleware;

use Cake\Http\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JwtMiddleware implements MiddlewareInterface
{
    private string $secretKey = 'concacon';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();

        // Các đường dẫn không yêu cầu xác thực token
        $excludedPaths = [
            '/users/api-login',
            '/users/register',
            '/users/login',
        ];

        // Bỏ qua kiểm tra token cho những đường dẫn trên
        if (in_array($path, $excludedPaths)) {
            return $handler->handle($request);
        }

        // Kiểm tra Authorization header
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            // Không có token hoặc token không hợp lệ, trả về thông báo
            return (new Response())
                ->withStatus(401)
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'error',
                    'message' => 'Bạn chưa đăng nhập.',
                ]));
        }

        // Lấy token từ header
        $token = substr($authHeader, 7);

        try {
            // Giải mã token
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            // Đưa payload vào request để sử dụng trong controller
            $request = $request->withAttribute('jwt_payload', (array)$decoded);
        } catch (\Exception $e) {
            // Token không hợp lệ hoặc đã hết hạn
            return (new Response())
                ->withStatus(401)
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'error',
                    'message' => 'Token không hợp lệ hoặc đã hết hạn.',
                ]));
        }

        // Tiếp tục xử lý request nếu token hợp lệ
        return $handler->handle($request);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\UnauthorizedException;
use Cake\Log\Log;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public function login()
    {
        if ($this->request->is('post')) {
            // Kiểm tra đăng nhập và trả về JSON nếu hợp lệ
            $result = $this->Authentication->getResult();

            // Kiểm tra nếu đăng nhập không hợp lệ hoặc không có người dùng
            if (!$result->isValid()) {
                return $this->response->withStatus(401)
                    ->withType('application/json')
                    ->withStringBody(json_encode(['message' => 'Sai thông tin đăng nhập'])); // Mật khẩu sai
            }

            $user = $result->getData();

            // Kiểm tra nếu không có người dùng (user là null)
            if (!$user) {
                return $this->response->withStatus(401)
                    ->withType('application/json')
                    ->withStringBody(json_encode(['message' => 'Sai thông tin đăng nhập'])); // Người dùng không tồn tại
            }

            // Kiểm tra lại email từ dữ liệu người dùng
            $emailFromRequest = $this->request->getData('email'); // Lấy email từ request
            if ($user->email !== $emailFromRequest) {
                return $this->response->withStatus(401)
                    ->withType('application/json')
                    ->withStringBody(json_encode(['message' => 'Sai email đăng nhập'])); // Email không khớp
            }

            // Kiểm tra mật khẩu mã hóa
            $passwordFromRequest = $this->request->getData('password'); // Lấy mật khẩu từ request
            if (!password_verify($passwordFromRequest, $user->password)) { // Sử dụng password_verify để kiểm tra mật khẩu đã mã hóa
                return $this->response->withStatus(401)
                    ->withType('application/json')
                    ->withStringBody(json_encode(['message' => 'Sai mật khẩu đăng nhập'])); // Mật khẩu không khớp
            }

            // Chỉ tạo token khi người dùng hợp lệ
            $key = 'concacon'; // secret key
            $payload = [
                'sub' => $user->id,
                'exp' => time() + 604800, // 7 ngày
            ];

            // Sinh JWT token
            $jwt = JWT::encode($payload, $key, 'HS256');

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'token' => $jwt,
                    'user' => ['id' => $user->id, 'email' => $user->email],
                ]));
        }

        // Trả về giao diện đăng nhập nếu yêu cầu là GET
        $this->render('login'); // Render giao diện đăng nhập
    }

    public function logout()
    {
        $this->Authentication->logout();
        return $this->redirect(['action' => 'login']);
    }

    public function register()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success('Đăng ký thành công.');
                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error('Không thể đăng ký.');
        }
        $this->set(compact('user'));
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\UnauthorizedException;
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
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();

        if ($result->isValid()) {
            return $this->redirect(['controller' => 'Articles', 'action' => 'index']);
        }

        if ($this->request->is('post')) {
            $this->Flash->error('Email hoặc mật khẩu không đúng.');
        }
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

    public function apiLogin()
    {
        $this->request->allowMethod(['post']);
        $result = $this->Authentication->getResult();

        if (!$result->isValid()) {
            return $this->response->withStatus(401)->withType('application/json')
                ->withStringBody(json_encode(['message' => 'Sai thông tin đăng nhập']));
        }

        /** @var \App\Model\Entity\User $user */
        $user = $result->getData();

        $key = 'concacon'; // giống trong config JWT
        $payload = [
            'sub' => $user->id,
            'exp' => time() + 604800, // 7 ngày
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'token' => $jwt,
                'user' => ['id' => $user->id, 'email' => $user->email],
            ]));
    }
}

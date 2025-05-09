<?php

declare(strict_types=1);

namespace App\Controller;

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
}

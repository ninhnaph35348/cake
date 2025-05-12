<?php

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\MethodNotAllowedException;

class ArticlesController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setClassName('Json');
        if ($this->request->is('ajax') || $this->request->is('json')) {
            Configure::write('DebugKit.forceEnable', false);
        }
        $this->autoRender = false;
    }

    public function index()
    {
        try {
            $articles = $this->Articles->find('all')->toArray();
            $formattedArticles = array_map(function ($article) {
                return [
                    'id' => $article->id,
                    'user_id' => $article->user_id,
                    'title' => $article->title,
                    'body' => $article->body,
                    'published' => $article->published,
                    'created' => $article->created->format('Y-m-d H:i:s'),
                    'modified' => $article->modified->format('Y-m-d H:i:s'),
                ];
            }, $articles);

            $this->set([
                'status' => 'success',
                'data' => $formattedArticles,
            ]);
        } catch (\Exception $e) {
            $this->response = $this->response->withStatus(500);
            $this->set([
                'status' => 'error',
                'message' => 'Lỗi khi lấy danh sách bài viết: ' . $e->getMessage(),
            ]);
        }
        $this->viewBuilder()->setOption('serialize', ['status', 'data', 'message']);
    }

    public function view($id = null)
    {
        try {
            $article = $this->Articles->get($id);
            $this->set([
                'status' => 'success',
                'data' => [
                    'id' => $article->id,
                    'user_id' => $article->user_id,
                    'title' => $article->title,
                    'body' => $article->body,
                    'published' => $article->published,
                    'created' => $article->created->format('Y-m-d H:i:s'),
                    'modified' => $article->modified->format('Y-m-d H:i:s'),
                ],
            ]);
        } catch (RecordNotFoundException $e) {
            $this->response = $this->response->withStatus(404);
            $this->set([
                'status' => 'error',
                'message' => 'Không tìm thấy bài viết.',
            ]);
        }
        $this->viewBuilder()->setOption('serialize', ['status', 'data', 'message']);
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['user_id'] = 1; // Thay bằng logic xác thực user
            $article = $this->Articles->newEntity($data);

            if ($this->Articles->save($article)) {
                $this->set([
                    'status' => 'success',
                    'data' => [
                        'id' => $article->id,
                        'title' => $article->title,
                    ],
                    'message' => 'Bài viết đã được lưu.',
                ]);
            } else {
                $this->response = $this->response->withStatus(400);
                $this->set([
                    'status' => 'error',
                    'message' => 'Không thể thêm bài viết.',
                    'errors' => $article->getErrors(),
                ]);
            }
        } else {
            throw new MethodNotAllowedException();
        }
        $this->viewBuilder()->setOption('serialize', ['status', 'data', 'message', 'errors']);
    }

    public function edit($id = null)
    {
        try {
            $article = $this->Articles->get($id);
            if ($this->request->is(['put', 'patch'])) {
                $article = $this->Articles->patchEntity($article, $this->request->getData());
                if ($this->Articles->save($article)) {
                    $this->set([
                        'status' => 'success',
                        'data' => [
                            'id' => $article->id,
                            'title' => $article->title,
                        ],
                        'message' => 'Bài viết đã được cập nhật.',
                    ]);
                } else {
                    $this->response = $this->response->withStatus(400);
                    $this->set([
                        'status' => 'error',
                        'message' => 'Không thể cập nhật bài viết.',
                        'errors' => $article->getErrors(),
                    ]);
                }
            } else {
                throw new MethodNotAllowedException();
            }
        } catch (RecordNotFoundException $e) {
            $this->response = $this->response->withStatus(404);
            $this->set([
                'status' => 'error',
                'message' => 'Không tìm thấy bài viết.',
            ]);
        }
        $this->viewBuilder()->setOption('serialize', ['status', 'data', 'message', 'errors']);
    }

    public function delete($id = null)
    {
        try {
            $this->request->allowMethod(['delete']);
            $article = $this->Articles->get($id);
            if ($this->Articles->delete($article)) {
                $this->set([
                    'status' => 'success',
                    'message' => 'Bài viết đã được xóa.',
                ]);
            } else {
                $this->response = $this->response->withStatus(400);
                $this->set([
                    'status' => 'error',
                    'message' => 'Không thể xóa bài viết.',
                ]);
            }
        } catch (RecordNotFoundException $e) {
            $this->response = $this->response->withStatus(404);
            $this->set([
                'status' => 'error',
                'message' => 'Không tìm thấy bài viết.',
            ]);
        }
        $this->viewBuilder()->setOption('serialize', ['status', 'message']);
    }
}

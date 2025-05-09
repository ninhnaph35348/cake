<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

        $articles = $this->paginate($this->Articles);

        $this->set(compact('articles'));
    }

    /**
     * View method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $article = $this->Articles->get($id);

        if (!$article) {
            $this->Flash->error('Không tìm thấy bài viết này.');
            return $this->redirect(['action' => 'index']);
        }

        $this->set(compact('article'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['user_id'] = 1;

            $article = $this->Articles->newEntity($data);


            if ($this->Articles->save($article)) {
                $this->Flash->success('Bài viết của bạn đã được lưu.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Không thể thêm bài viết của bạn.');
        }

        $this->set('article', $article ?? $this->Articles->newEmptyEntity());
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(string $id): \Cake\Http\Response|null
    {
        $article = $this->Articles->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            if ($this->Articles->save($article)) {
                $this->Flash->success('Bài viết của bạn đã được cập nhật.');
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error('Không thể cập nhật bài viết.');
        }

        $this->set(compact('article'));
        return null;
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(string $id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('Bài viết đã được xóa.'));
        } else {
            $this->Flash->error(__('Không thể xóa bài viết. Vui lòng thử lại.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

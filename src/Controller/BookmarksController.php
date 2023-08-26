<?php

declare(strict_types=1);

namespace App\Controller;

class BookmarksController extends AppController
{

    public function index()
    {
        $this->paginate = [
            'conditions' => [
                'Bookmarks.user_id' => $this->Auth->user('id'),
            ]
        ];

        $this->set('bookmarks', $this->paginate($this->Bookmarks));
        $this->set('_serialize', ['bookmarks']);
    }

    public function view($id = null)
    {
        $bookmark = $this->Bookmarks->get($id, [
            'contain' => ['Users', 'Tags'],
        ]);

        $this->set(compact('bookmark'));
    }


    public function add()
    {
        $bookmark = $this->Bookmarks->newEntity([]);

        if ($this->request->is('post')) {
            $bookmark = $this->Bookmarks->patchEntity($bookmark, $this->request->getData());
            $bookmark->user_id = $this->Auth->user('id');

            if ($this->Bookmarks->save($bookmark)) {
                $this->Flash->success(__('The bookmark has been saved.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The bookmark could not be saved. Please, try again.'));
        }

        // $users = $this->Bookmarks->Users->find('list', ['limit' => 200])->all();
        $tags = $this->Bookmarks->Tags->find('list', ['limit' => 200])->all();
        $this->set(compact('bookmark', 'tags'));
        $this->set('_serialize', ['bookmark']);
    }


    public function edit($id = null)
    {
        $bookmark = $this->Bookmarks->get($id, [
            'contain' => ['Tags'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            $bookmark = $this->Bookmarks->patchEntity($bookmark, $this->request->getData());
            $bookmark->user_id = $this->Auth->user('id');

            if ($this->Bookmarks->save($bookmark)) {
                $this->Flash->success(__('The bookmark has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bookmark could not be saved. Please, try again.'));
        }
        // $users = $this->Bookmarks->Users->find('list', ['limit' => 200])->all();
        $tags = $this->Bookmarks->Tags->find('list', ['limit' => 200])->all();
        $this->set(compact('bookmark', 'tags'));
        $this->set('_serialize', ['bookmark']);
    }


    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bookmark = $this->Bookmarks->get($id);
        if ($this->Bookmarks->delete($bookmark)) {
            $this->Flash->success(__('The bookmark has been deleted.'));
        } else {
            $this->Flash->error(__('The bookmark could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    // ACTIONS BY ME:

    public function tags()
    {
        $tags = $this->request->getParam('pass');

        $bookmarks = $this->Bookmarks->find('tagged', ['tags' => $tags]);

        $this->set(['bookmarks' => $bookmarks, 'tags' => $tags]);
    }

    public function isAuthorized($user){
        $action = $this->request->getParam('action');

        if(in_array($action, ['index', 'add', 'tags'])){
            return true;
        }

        //this will check id == pass.0 or actions
        if(!$this->request->getParam('pass.0')){
            return false;
        }

        $id = $this->request->getParam('pass.0');
        $bookmark = $this->Bookmarks->get($id);

        if($bookmark->user_id == $user['id']){
            return true;
        }


        return parent::isAuthorized($user);
    }
}

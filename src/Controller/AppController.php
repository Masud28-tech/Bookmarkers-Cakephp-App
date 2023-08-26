<?php

declare(strict_types=1);


namespace App\Controller;

use Cake\Controller\Controller;


class AppController extends Controller
{

    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('RequestHandler');

        $this->loadComponent('Auth', [
            'authorize' => 'Controller',

            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password',
                    ]
                ]
            ],

            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login',
            ],

            'unauthorizedRedirect' => $this->referer()
        ]);
    }

    public function isAuthorized($user)
    {
        return false;
    }
}

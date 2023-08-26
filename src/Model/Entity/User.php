<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

class User extends Entity
{

    protected $_accessible = [
        'email' => true,
        'password' => true,
        'created' => true,
        'modified' => true,
        'bookmarks' => true,
    ];

    protected $_hidden = [
        'password',
    ];

    protected function _setPassword($value){
        $hasher = new DefaultPasswordHasher();

        return $hasher->hash($value);
    }
}

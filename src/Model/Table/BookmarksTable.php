<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class BookmarksTable extends Table
{
 
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('bookmarks');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsToMany('Tags', [
            'foreignKey' => 'bookmark_id',
            'targetForeignKey' => 'tag_id',
            'joinTable' => 'bookmarks_tags',
        ]);
    }

  
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->scalar('title')
            ->maxLength('title', 50)
            ->allowEmptyString('title');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('url')
            ->allowEmptyString('url');

        return $validator;
    }

  
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }

    // THIS METHOD FINDS ALL BOOKMARKS WITH GIVEN TAGS (PASSED IN OPTIONS ARRAY)
    // IF TAGS ARRAY IS EMPTY THEN PASS ALL THE BOOKMARKS WITH TAGS NULL

    public function findTagged(Query $query, array $options)
    {
        $bookmarks = $this->find()->select(['id', 'url', 'title', 'description']);

        if(empty($options['tags'])){
            $bookmarks->leftJoinWith('Tags')->where(['Tags.title Is' => null]);
        }
        else{
            $bookmarks->innerJoinWith('Tags')->where(['Tags.title In' => $options['tags']]);
        }

        return $bookmarks->group(['Bookmarks.id']);
    }
}

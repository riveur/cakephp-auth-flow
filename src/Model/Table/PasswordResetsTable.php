<?php

namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\I18n\FrozenTime;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Exception;

/**
 * PasswordResets Model
 *
 * @method \App\Model\Entity\PasswordReset get($primaryKey, $options = [])
 * @method \App\Model\Entity\PasswordReset newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PasswordReset[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PasswordReset|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PasswordReset saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PasswordReset patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PasswordReset[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PasswordReset findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PasswordResetsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('password_resets');
        $this->setDisplayField('email');
        $this->setPrimaryKey('email');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->email('email')
            ->allowEmptyString('email', null, 'create');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->requirePresence('token', 'create')
            ->notEmptyString('token');

        $validator
            ->dateTime('expires_at')
            ->allowEmptyDateTime('expires_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }

    /**
     * Create a password reset token for passed email.
     *
     * @param string $email The email refers to account need the password reset
     * @param string $token The token string
     * @param int $expirationTime Time for token validity in minutes
     * @return string The token.
     */
    public function create($email, $token, $expirationTime)
    {
        $data = [
            'email' => $email,
            'token' => $token,
            'created' => FrozenTime::now(),
            'expires_at' => FrozenTime::now()->addMinutes($expirationTime)
        ];

        $exists = $this->exists(['email' => $email]);

        if ($exists) {
            $passwordReset = $this->find()->where(['email' => $email])->first();
            $passwordReset = $this->patchEntity($passwordReset, $data, [
                'accessibleFields' => [
                    'email' => false
                ]
            ]);
        } else {
            $passwordReset = $this->newEntity($data);
        }

        try {
            $passwordReset = $this->saveOrFail($passwordReset);
        } catch (PersistenceFailedException $ex) {
            throw new Exception(sprintf("An error occured when saving `%s`", get_class($ex->getEntity())));
        }

        return $passwordReset->token;
    }

    /**
     * Delete all expired tokens
     *
     * @return int Number of rows affected
     */
    public function deleteExpired()
    {
        return $this->deleteAll(function (QueryExpression $exp) {
            return $exp->lt('expires_at', FrozenTime::now());
        });
    }
}

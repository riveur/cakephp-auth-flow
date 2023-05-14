<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * Login Form.
 */
class LoginForm extends Form
{
    /**
     * Builds the schema for the modelless form
     *
     * @param \Cake\Form\Schema $schema From schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        $schema
            ->addField('email', ['type' => 'email'])
            ->addField('password', ['type' => 'password']);

        return $schema;
    }

    /**
     * Form validation builder
     *
     * @param \Cake\Validation\Validator $validator to use against the form
     * @return \Cake\Validation\Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->email('email')
            ->notEmptyString('email');

        $validator
            ->notEmptyString('password');


        return $validator;
    }
}

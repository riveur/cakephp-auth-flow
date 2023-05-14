<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * PasswordReset Form.
 */
class PasswordResetForm extends Form
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
            ->addField('password', ['type' => 'password'])
            ->addField('password_confirm', ['type' => 'password']);

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
            ->notEmptyString('password');

        $validator
            ->notEmptyString('password_confirm')
            ->sameAs('password_confirm', 'password', 'Passwords mismath');

        return $validator;
    }
}

<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * PasswordChange Form.
 */
class PasswordChangeForm extends Form
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
            ->addField('current_password', ['type' => 'password'])
            ->addField('new_password', ['type' => 'password'])
            ->addField('new_password_confirm', ['type' => 'password']);

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
            ->notEmptyString('current_password');

        $validator
            ->notEmptyString('new_password');

        $validator
            ->notEmptyString('new_password_confirm')
            ->sameAs('new_password_confirm', 'new_password', 'Passwords mismatch');

        return $validator;
    }
}

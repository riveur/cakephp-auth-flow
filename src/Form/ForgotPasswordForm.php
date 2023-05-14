<?php

namespace App\Form;

use App\Services\Authentication;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * ForgotPassword Form.
 */
class ForgotPasswordForm extends Form
{
    /**
     * Builds the schema for the modelless form
     *
     * @param \Cake\Form\Schema $schema From schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema)
    {
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

        return $validator;
    }

    /**
     * Defines what to execute once the Form is processed
     *
     * @param array $data Form data.
     * @return bool
     */
    protected function _execute(array $data)
    {
        if (!$this->validate($data)) {
            return false;
        }

        $service = new Authentication();

        return $service->sendPasswordResetMail($data['email']);
    }
}

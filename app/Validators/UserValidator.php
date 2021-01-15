<?php

namespace App\Validators;

class UserValidator extends BaseValidator
{
    /**
     * Validation rules for this validator
     *
     * @var array
     */
    public $rules = [

        'create' => [
            'name' => 'required',
			'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'type' => 'required'
        ],

        'update' => [
            'name' => 'required',
			'email' => 'required|email|unique:users,email',
            'password' => 'nullable|min:6|confirmed',
            'type' => 'required'
        ],
    ];
}

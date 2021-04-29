<?php

namespace App\Validators;

class SocialMediaCheckListValidator extends BaseValidator
{
    /**
     * Validation rules for this validator
     *
     * @var array
     */
    public $rules = [

        'create' => [
            'target' => 'required',
            'text' => 'required',
        ],

        'update' => [
            'target' => 'required',
            'text' => 'required',
        ],
    ];
}

<?php

namespace App\Validators;

class FinancialReportValidator extends BaseValidator
{
    /**
     * Validation rules for this validator
     *
     * @var array
     */
    public $rules = [

        'create' => [
            'date' => 'required|date|unique:financial_reports,date',
            'profits' => 'required|array',
            'expenses' => 'required|array'
        ],

        'update' => [
            'profits' => 'required|array',
            'expenses' => 'required|array'
        ],
    ];
}

<?php namespace App\Validators;

use App\Models\Column;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;
use Validator;

class BaseValidator {

    protected $validator;

    /**
     * The class of the model being validated
     *
     * @var string
     */
    protected $modelClass = '';

    /**
     * Validation rules for this validator
     *
     * @var array
     */
    protected $rules = [

        'create' => [

        ],

        'update' => [

        ],
    ];

    /**
     * Validation messages for this validator
     *
     * @var array
     */
    protected $messages = [

        'create' => [

        ],

        'update' => [

        ],
    ];

    protected $customAttributes = [];

    /**
     * Array to hold validation error messages
     *
     * @var array
     */
    protected $errors = [];

    /**
     * New Validator instance
     */
    public function __construct()
    {
        $this->errors = new MessageBag;
    }

    /**
     * Perform validation
     * @param  Array or Collection $data    The data to validate
     * @param  string $ruleset The ruleset to use
     * @return boolean          Validator result
     */
    public function validate($data, $ruleset = 'create')
    {
        // We allow collections, so transform to array.
        if ($data instanceof Collection) {
            $data = $data->all();
        }

        $this->addRulesForCustomFields();

        // Load the correct ruleset.
        $rules = $this->rules[$ruleset];

        // Load the correct messageset.
        $messages = $this->messages[$ruleset];

        // Create the validator instance and validate.
        $this->validator = Validator::make($data, $rules, $messages, $this->customAttributes);
        if (!$result = $this->validator->passes()) {
            $this->errors = $this->validator->messages();
        }

        return $result;
    }

    /**
     * Get the validator instance
     *
     * @return Validator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Get the validation errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get the errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->getErrors();
    }

    /**
     * Add an id to ignore for the unique validation rule
     *
     * @param  string $ruleset
     * @param  string $field
     * @param  integer $id
     *
     * @return void
     */
    public function ignoreIdForUniqueRule($ruleset, $field, $id)
    {
        $rule = $this->rules[$ruleset][$field];
        $rules = explode('|', $rule);

        foreach ($rules as $key => $rule) {
            // Try to match a unique rule
            if (preg_match('/unique:(.+?)(?:$|,)(?:$|(?:(.+?)(?:$|,)))(?:$|(?:(.+?)(?:$|,)))/', $rule, $matches)) {
                if (count($matches) == 3) {
                    $rules[$key] .= ',' . $id;
                }

                break;
            }
        }

        $rule = implode('|', $rules);

        $this->rules[$ruleset][$field] = $rule;
    }

    /**
     * Add rule
     *
     * @param string $ruleset
     * @param string $field
     * @param string $rule
     */
    public function addRule($ruleset, $field, $rule)
    {
        if (isset($this->rules[$ruleset][$field])) {
            $rule = $this->rules[$ruleset][$field] . '|' . $rule;
        }

        $this->rules[$ruleset][$field] = $rule;
    }

    /**
     * @param bool|callable $condition
     * @param string $ruleset
     * @param string $field
     * @param string $rule
     */
    public function addRuleWhen($condition, string $ruleset, string $field, string $rule) : void
    {
        $addRule = (is_callable($condition) && $condition()) || $condition === true;

        if ($addRule) {
            $this->addRule($ruleset, $field, $rule);
        }
    }

    /**
     * Add rules for validating layout column
     */
    protected function addLayoutColumnRules()
    {
        $rules = [];

        foreach (config('cms.columns') as $columnKey => $columnName) {
            $rules['layout_column_'.$columnKey] = 'required_if:layout,custom|' . Rule::in(array_merge(['0', 'default'], Column::pluck('id')->toArray()));
        }

        foreach ($rules as $field => $rule) {
            $this->addRule('create', $field, $rule);
            $this->addRule('update', $field, $rule);
        }
    }

    /**
     * Remove a rule
     *
     * @param $ruleset
     * @param $field
     * @param null $rule
     * @return bool
     */
    public function removeRule($ruleset, $field, $rule = null)
    {
        if (!isset($this->rules[$ruleset][$field])) {
            return false;
        }

        if (is_null($rule)) {
            // Remove entire field
            unset($this->rules[$ruleset][$field]);
        } else {
            // remove a specific rule
            $rules = $this->rules[$ruleset][$field];
            $rules = explode('|', $rules);
            foreach ($rules as $key => $aRule) {
                $ruleParts = explode(',', $aRule);
                if (count($ruleParts) > 0 && $ruleParts[0] == $rule) {
                    // found specific rule
                    unset($rules[$key]);
                    break;
                }
            }

            if (empty($rules)) {
                unset($this->rules[$ruleset][$field]);
            } else {
                $this->rules = implode('|', $rules);
            }
        }
    }

    /**
     * Add rules for custom product fields
     */
    protected function addRulesForCustomFields()
    {
        if (! $this->modelClass) {
            return;
        }

        if (! in_array('App\\Traits\\HasCustomFields', class_uses($this->modelClass))) {
            return;
        }

        $className = $this->modelClass;

        foreach ($className::customFields() as $field) {
            if (!empty($field->rules)) {
                $this->addRule('create', $field->key, $field->rules);
                $this->addRule('update', $field->key, $field->rules);
            }

            if ($field->type == 'image') {
                $this->addRule('create', $field->key, 'exists:files,id');
                $this->addRule('update', $field->key, 'exists:files,id');
            }

            if (in_array($field->type, ['radio', 'select'])) {
                $this->addRule('create', $field->key, 'in:'.implode(',', array_keys($field->options())));
                $this->addRule('update', $field->key, 'in:'.implode(',', array_keys($field->options())));
            }

            if ($field->type == 'checkbox' && $field->hasOptions()) {
                $this->addRule('create', $field->key . '.*', 'in:'.implode(',', array_keys($field->options())));
                $this->addRule('update', $field->key . '.*', 'in:'.implode(',', array_keys($field->options())));
            }

            if ($field->type == 'price') {
                $this->addRule('create', $field->key, 'numeric');
                $this->addRule('update', $field->key, 'numeric');
            }
        }
    }
}

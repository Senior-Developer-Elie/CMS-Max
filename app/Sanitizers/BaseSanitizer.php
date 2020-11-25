<?php

namespace App\Sanitizers;

use Carbon\Carbon;
use Illuminate\Http\Request;

abstract class BaseSanitizer
{
	/**
	 * An array of sanitizer methods to be executed.
	 *
	 * @var array
	 */
	protected $sanitizers = [];

	/**
	 * An array of the input data
	 *
	 * @var array
	 */
	public $data;

    /**
     * The model for which the data is being santiized
     *
     * @var mixed|null
     */
    protected $model = null;

    /**
     * The class of the model that is being sanitized
     *
     * @var string
     */
    protected $modelClass = '';

    /**
     * New Sanitizer instance
     * @param mixed $model
     */
	public function __construct($model = null)
	{
        $this->model = $model;
	}

	/**
	 * Trigger the sanitization process by iterating the sanitizers
	 * array and mutating our data array
	 *
	 * @param  array $data
	 * @return array
	 */
	public function sanitize($data)
	{
		$this->data = $data;

        if ($this->modelClass && in_array('App\\Traits\\HasCustomFields', class_uses($this->modelClass))) {
            $this->sanitizers[] = 'CustomFields';
        }

		// Iterate all of the santizer methods.
		foreach ($this->sanitizers as $sanitizer) {
			$method = 'sanitize'.$sanitizer;
			// If the sanitizer method exists, call it to mutate our data set
			if (method_exists($this, $method)) {
				call_user_func([$this, $method]);
			}
		}

		return $this->data;
	}

	/**
	 * Sanitize request data
	 *
	 * @param  Request $request
	 *
	 * @return void
	 */
	public function sanitizeRequest(Request $request)
	{
		$data = $request->all();
		$data = $this->sanitize($data);
		$request->replace($data);
	}

	/**
	 * Upper Case Name
	 *
	 * @param  string $name
	 *
	 * @return string $name
	 */
	protected function ucname($name) {
	    $name = ucwords(strtolower($name));

		foreach (array('-', '\'') as $delimiter) {
			if (strpos($name, $delimiter) !== false) {
				$name =implode($delimiter, array_map('ucfirst', explode($delimiter, $name)));
			}
		}

	    return $name;
	}

	protected function has($field)
    {
        return \Illuminate\Support\Arr::has($this->data, $field);
    }

    protected function filled($field)
    {
        if (! $this->has($field)) {
            return false;
        }

        return ! empty($this->get($field));
    }

    protected function get($field)
    {
        return \Illuminate\Support\Arr::get($this->data, $field);
    }

    protected function set($field, $value)
    {
        \Illuminate\Support\Arr::set($this->data, $field, $value);
    }

	/**
	 * Sanitize checkbox. If field is set, value will be 1, else 0
	 *
	 * @param  string $field
	 *
	 * @return void
	 */
	protected function checkbox($field)
	{
		if ($this->has($field)) {
		    $this->set($field, 1);
		} else {
            $this->set($field, 0);
		}
	}

    /**
     * Trim fields
     *
     * @param $fields
     */
	protected function trimFields($fields)
    {
        if (! is_array($fields)) {
            $fields = [$fields];
        }

        foreach ($fields as $field) {
            if ($this->has($field)) {
                $this->set($field, trim($this->get($field)));
            }
        }
    }

    /**
     * Nullify a field if it's not set or is empty
     *
     * @param $field
     */
    protected function nullify($field)
    {
        if (! $this->filled($field)) {
            $this->set($field, null);
        }
    }

    /**
     * Keep only digits
     *
     * @param $field
     */
    protected function digitify($field)
    {
        if ($this->has($field)) {
            $this->set($field, preg_replace('/\D/', '', $this->get($field)));
        }
    }

    protected function dateify($field)
    {
        if ($this->filled($field)) {
            $this->set($field, Carbon::parse($this->get($field))->format('Y-m-d'));
        }
    }

    public function sanitizeCustomFields()
    {
        $className = $this->modelClass;

        foreach ($className::customFields() as $field) {
            switch ($field->type) {
                case 'checkbox':
                    if (! $field->hasOptions()) {
                        $this->checkbox($field->key);
                    }
                    break;
                case 'image':
                    if ($this->has($field->key) && empty($this->data[$field->key])) {
                        $this->set($field->key, '');
                    }
                    break;
                case 'price':
                    if ($this->has($field->key)) {
                        $this->set($field->key, str_replace(',', '', $this->data[$field->key]));
                        $this->set($field->key, floatval($this->data[$field->key]));
                        $this->set($field->key, round($this->data[$field->key], 2));
                    }
                    break;
                case 'list':
                    $fieldValues = [];
                    if ($this->has($field->key) && is_array($this->data[$field->key])) {
                        foreach ($this->data[$field->key] as $key => $value) {
                            $value = trim($value);
                            if (!empty($value)) {
                                $fieldValues[] = $value;
                            }
                        }
                    }
                    $this->set($field->key, $fieldValues);
                    break;
            }
        }
    }

    protected function custom(string $field, callable $callback) : void
    {
        $this->set($field, $callback($this->get($field)));
    }
}

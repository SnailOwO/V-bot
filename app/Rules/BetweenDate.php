<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BetweenDate implements Rule {
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    private $rules = [
        'create.*' => 'nullable|date_format:Y-m-d H:i:s',
        'update.*' => 'nullable|date_format:Y-m-d H:i:s',
    ];

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) {
        if(is_array($value)) {
            if(count($value) > 2) {
                return true;
            } else {    // 可以为空,长度可以为1   
                return customValidate($value,$this->rules) ?: false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return ts('custom.dateParamIllegal');
    }
}

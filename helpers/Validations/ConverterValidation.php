<?php

namespace Helpers\Validations;

use App\Models\Currency;

class ConverterValidation
{
    /**
     * @param array $fields
     * @return array
     */
    public static function validate(array &$fields): array
    {
        $errors = [];

        foreach ($fields as $key => $item) {
            if (empty($item)) {
                $errors[$key] = "Fill in the field $key";
            }
        }

        if (!ctype_digit($fields['currency_from']) || !Currency::find($fields['currency_from'])) {
            $errors['currency_from'] = "'Currency from' value is invalid! Don't change 'currency from' value!";
        }

        if (!ctype_digit($fields['currency_to']) || !Currency::find($fields['currency_to'])) {
            $errors['currency_to'] = "'Currency to' value is invalid! Don't change 'currency to' value!";
        }

        if (!is_numeric($fields['requested_value']) || $fields['requested_value'] <= 0) {
            $errors['requested_value'] = "Requested value is invalid! The variable must be a number greater than zero!";
        }

        foreach ($fields as $key => $item) {
            $fields[$key] = htmlspecialchars($item);
        }

        return $errors;
    }
}
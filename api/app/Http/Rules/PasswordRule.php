<?php

namespace SchedulingTerms\App\Http\Rules;

use Rakit\Validation\Rule;

class PasswordRule extends Rule
{
    protected $message = "The :attribute must be at least 8 characters long, contain at least one uppercase letter, and at least one number.";

    public function check($value): bool
    {
        $minLength = 8;

        if (strlen($value) < $minLength) {
            return false;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            return false;
        }

        if (!preg_match('/[0-9]/', $value)) {
            return false;
        }

        return true;
    }
}
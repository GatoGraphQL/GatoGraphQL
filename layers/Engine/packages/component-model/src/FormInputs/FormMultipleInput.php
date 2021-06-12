<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FormInputs;

class FormMultipleInput extends FormInput
{
    public function isMultiple(): bool
    {
        return true;
    }
}

<?php

declare(strict_types=1);

namespace PoP\Engine\FormInputs;

class MultipleSelectFormInput extends SelectFormInput
{
    public function isMultiple()
    {
        return true;
    }
}

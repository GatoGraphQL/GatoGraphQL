<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FormInputs;

use PoP\ComponentModel\FormInputs\FormInput;
use PoP\ComponentModel\Tokens\Param;

class MultiValueFromStringFormInput extends FormInput
{
    private $separator;

    public function __construct(string $name, mixed $selected = null, array $params = [])
    {
        parent::__construct($name, $selected, $params);
        $this->separator = $params['separator'] ?? Param::VALUE_SEPARATOR;
    }

    public function getValue(?array $source = null): mixed
    {
        $value = parent::getValue($source);
        // Only if it is not null process it
        if (!is_null($value)) {
            return array_map('trim', explode($this->separator, $value));
        }
        return $value;
    }
}

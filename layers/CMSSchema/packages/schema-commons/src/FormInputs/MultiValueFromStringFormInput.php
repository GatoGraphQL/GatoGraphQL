<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FormInputs;

use PoP\ComponentModel\FormInputs\FormInput;
use PoP\ComponentModel\Tokens\Param;

class MultiValueFromStringFormInput extends FormInput
{
    private string $separator;

    /**
     * @param array<string,mixed> $params
     */
    public function __construct(string $name, mixed $selected = null, array $params = [])
    {
        parent::__construct($name, $selected, $params);
        $this->separator = $params['separator'] ?? Param::VALUE_SEPARATOR;
    }

    /**
     * @param array<string,mixed>|null $source
     */
    public function getValue(?array $source = null): mixed
    {
        $value = parent::getValue($source);
        // Only if it is not null process it
        if ($value === null) {
            return $value;
        }
        return array_map(trim(...), explode($this->separator, $value));
    }
}

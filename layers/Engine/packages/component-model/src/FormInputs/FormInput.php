<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FormInputs;

class FormInput
{
    public string $name;
    public ?string $selected = null;

    public function __construct($params = array())
    {
        $this->name = $params['name'];

        // Selected value. If provided, use it
        $this->selected = $params['selected'] ?? null;
    }

    public function isMultiple(): bool
    {
        return false;
    }

    protected function getValueFromSource(array $source): mixed
    {
        // If not set, it will be NULL
        $value =  $source[$this->getName()] ?? null;

        // If it is multiple and the URL contains an empty value (eg: &searchfor[]=&), it will interpret it as array(''),
        // but instead it must be an empty array
        // if ($this->isMultiple() && $value && count($value) == 1 && $value[0] == '') {

        //     $value = array();
        // }
        if ($this->isMultiple() && !is_null($value)) {
            $value = array_filter($value);
        }

        return $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * $_REQUEST has priority (for when editing post / user data, after submitting form this will override original post / user metadata values)
     */
    public function getValue(?array $source = null): mixed
    {
        // Empty values (eg: '', array()) can be the value. Only if NULL get a default value
        if (!is_null($this->selected)) {
            return $this->selected;
        }

        // If no source is passed, then use the request
        $source = $source ?? $_REQUEST;

        $selected = $this->getValueFromSource($source);
        if (!is_null($selected)) {
            return $selected;
        }

        return $this->getDefaultValue();
    }

    /**
     * Function to override
     */
    public function getDefaultValue(): mixed
    {
        return null;
    }
}

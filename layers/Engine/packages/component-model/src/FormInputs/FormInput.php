<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FormInputs;

use PoP\Root\App;

class FormInput
{
    public string $name;
    public mixed $selected;
    public array $params;

    public function __construct(string $name, mixed $selected = null, array $params = [])
    {
        $this->name = $name;

        // Selected value. If provided, use it
        $this->selected = $selected;
        
        $this->params = $params;
    }

    public function isMultiple(): bool
    {
        return false;
    }

    protected function getValueFromSource(?array $source = null): mixed
    {
        $value = $this->getValueFromSourceOrRequest($source, $this->getName());

        // If it is multiple and the URL contains an empty value (eg: &searchfor[]=&), it will interpret it as array(''),
        // but instead it must be an empty array
        // if ($this->isMultiple() && $value && count($value) == 1 && $value[0] == '') {

        //     $value = array();
        // }
        if ($value !== null && $this->isMultiple()) {
            // Watch out passing a string as REST endpoint arg when array expected
            if (!is_array($value)) {
                $value = [$value];
            }
            return array_filter($value);
        }

        return $value;
    }

    protected function getValueFromSourceOrRequest(?array $source = null, string $name): mixed
    {
        // If not set, it will be NULL
        $value = null;
        if ($source !== null) {
            $value = $source[$name] ?? null;
        }
        return $value ?? App::request($name) ?? App::query($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * $_POST/$_GET has priority (for when editing post / user data, after submitting form this will override original post / user metadata values)
     */
    public function getValue(?array $source = null): mixed
    {
        // Empty values (eg: '', array()) can be the value. Only if NULL get a default value
        if ($this->selected !== null) {
            return $this->selected;
        }

        if (!$this->isInputSetInSource($source)) {
            return $this->getDefaultValue();
        }

        return $this->getValueFromSource($source);
    }

    /**
     * It checks if the key with the input's name has been set.
     * Setting `key=null` counts as `true`
     */
    public function isInputSetInSource(?array $source = null): bool
    {
        $name = $this->getName();
        return ($source !== null && array_key_exists($name, $source))
            || App::getRequest()->request->has($name)
            || App::getRequest()->query->has($name);
    }

    /**
     * Function to override
     */
    public function getDefaultValue(): mixed
    {
        return null;
    }
}

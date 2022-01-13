<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FormInputs;

use Exception;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class FormInput
{
    public string $name;
    public mixed $selected = null;

    public function __construct($params = array())
    {
        if (!isset($params['name'])) {
            $translationAPI = TranslationAPIFacade::getInstance();
            throw new Exception(
                $translationAPI->__('Mandatory property \'name\' in \'$params\' is missing', 'component-model')
            );
        }
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
        $value = $source[$this->getName()] ?? null;

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

        if (!$this->isInputSetInSource($source)) {
            return $this->getDefaultValue();
        }

        return $this->getValueFromSource($this->getSource($source));
    }

    /**
     * If no source is passed, then use the request
     */
    protected function getSource(?array $source = null): array
    {
        return $source ?? $_REQUEST;
    }

    /**
     * It checks if the key with the input's name has been set.
     * Setting `key=null` counts as `true`
     */
    public function isInputSetInSource(?array $source = null): bool
    {
        $source = $this->getSource($source);
        return array_key_exists($this->getName(), $source);
    }

    /**
     * Function to override
     */
    public function getDefaultValue(): mixed
    {
        return null;
    }
}

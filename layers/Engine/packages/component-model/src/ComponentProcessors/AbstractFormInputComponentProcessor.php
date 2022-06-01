<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\FormInputs\FormInput;
use PoP\ComponentModel\FormInputs\FormMultipleInput;

abstract class AbstractFormInputComponentProcessor extends AbstractQueryDataComponentProcessor implements FormInputComponentProcessorInterface
{
    /**
     * @var array<string,FormInput>
     */
    private array $formInputs = [];

    // This function CANNOT have $props, since multiple can change the value of the input (eg: from Select to MultiSelect => from '' to array())
    // Yet we do not always go through initModelProps to initialize it, then changing the multiple in the form through $props, and trying to retrieve the value in an actionexecuter will fail
    public function isMultiple(\PoP\ComponentModel\Component\Component $component): bool
    {
        return false;
    }

    public function getInputName(\PoP\ComponentModel\Component\Component $component): string
    {
        $name = $this->getName($component);
        return $name . ($this->isMultiple($component) ? '[]' : '');
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        if ($this->isMultiple($component)) {
            return FormMultipleInput::class;
        }

        return FormInput::class;
    }

    final public function getInput(\PoP\ComponentModel\Component\Component $component): FormInput
    {
        $inputName = $this->getName($component);
        if (!isset($this->formInputs[$inputName])) {
            $inputClass = $this->getInputClass($component);
            $this->formInputs[$inputName] = new $inputClass(
                $inputName,
                $this->getInputSelectedValue($component),
                $this->getInputOptions($component)
            );
        }
        return $this->formInputs[$inputName];
    }

    // This function CANNOT have $props, since we do not always go through initModelProps to set the name of the input
    // Eg: we change the input name through $props 'name' when displaying the form, however in the actionexecuter, it doesn't
    // load that same component (it just accesses directly its value), then it fails retrieving the value since it tries get it from a different field name
    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        return $this->getComponentHelpers()->getComponentOutputName($component);
    }

    public function getValue(\PoP\ComponentModel\Component\Component $component, ?array $source = null): mixed
    {
        return $this->getInput($component)->getValue($source);
    }

    public function isInputSetInSource(\PoP\ComponentModel\Component\Component $component, ?array $source = null): mixed
    {
        return $this->getInput($component)->isInputSetInSource($source);
    }

    public function getInputDefaultValue(\PoP\ComponentModel\Component\Component $component, array &$props): mixed
    {
        return null;
    }

    public function getDefaultValue(\PoP\ComponentModel\Component\Component $component, array &$props): mixed
    {
        $value = $this->getProp($component, $props, 'default-value');
        if (!is_null($value)) {
            return $value;
        }

        return $this->getInputDefaultValue($component, $props);
    }

    public function getInputSelectedValue(\PoP\ComponentModel\Component\Component $component): mixed
    {
        return null;
    }

    public function getInputOptions(\PoP\ComponentModel\Component\Component $component): array
    {
        return [];
    }
}

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
    public function isMultiple(array $componentVariation): bool
    {
        return false;
    }

    public function getInputName(array $componentVariation): string
    {
        $name = $this->getName($componentVariation);
        return $name . ($this->isMultiple($componentVariation) ? '[]' : '');
    }

    public function getInputClass(array $componentVariation): string
    {
        if ($this->isMultiple($componentVariation)) {
            return FormMultipleInput::class;
        }

        return FormInput::class;
    }

    final public function getInput(array $componentVariation): FormInput
    {
        $inputName = $this->getName($componentVariation);
        if (!isset($this->formInputs[$inputName])) {
            $inputClass = $this->getInputClass($componentVariation);
            $this->formInputs[$inputName] = new $inputClass(
                $inputName,
                $this->getInputSelectedValue($componentVariation),
                $this->getInputOptions($componentVariation)
            );
        }
        return $this->formInputs[$inputName];
    }

    // This function CANNOT have $props, since we do not always go through initModelProps to set the name of the input
    // Eg: we change the input name through $props 'name' when displaying the form, however in the actionexecuter, it doesn't
    // load that same module (it just accesses directly its value), then it fails retrieving the value since it tries get it from a different field name
    public function getName(array $componentVariation): string
    {
        return $this->getModuleHelpers()->getModuleOutputName($componentVariation);
    }

    public function getValue(array $componentVariation, ?array $source = null): mixed
    {
        return $this->getInput($componentVariation)->getValue($source);
    }

    public function isInputSetInSource(array $componentVariation, ?array $source = null): mixed
    {
        return $this->getInput($componentVariation)->isInputSetInSource($source);
    }

    public function getInputDefaultValue(array $componentVariation, array &$props): mixed
    {
        return null;
    }

    public function getDefaultValue(array $componentVariation, array &$props): mixed
    {
        $value = $this->getProp($componentVariation, $props, 'default-value');
        if (!is_null($value)) {
            return $value;
        }

        return $this->getInputDefaultValue($componentVariation, $props);
    }

    public function getInputSelectedValue(array $componentVariation): mixed
    {
        return null;
    }

    public function getInputOptions(array $componentVariation): array
    {
        return [];
    }
}

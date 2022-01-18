<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\FormInputs\FormInput;
use PoP\ComponentModel\FormInputs\FormMultipleInput;

abstract class AbstractFormInputModuleProcessor extends AbstractQueryDataModuleProcessor implements FormInputModuleProcessorInterface
{
    /**
     * @var array<string,FormInput>
     */
    private array $formInputs = [];

    // This function CANNOT have $props, since multiple can change the value of the input (eg: from Select to MultiSelect => from '' to array())
    // Yet we do not always go through initModelProps to initialize it, then changing the multiple in the form through $props, and trying to retrieve the value in an actionexecuter will fail
    public function isMultiple(array $module): bool
    {
        return false;
    }

    public function getInputName(array $module): string
    {
        $name = $this->getName($module);
        return $name . ($this->isMultiple($module) ? '[]' : '');
    }

    public function getInputClass(array $module): string
    {
        if ($this->isMultiple($module)) {
            return FormMultipleInput::class;
        }

        return FormInput::class;
    }

    final public function getInput(array $module): FormInput
    {
        $inputName = $this->getName($module);
        if (!isset($this->formInputs[$inputName])) {
            $options = $this->getInputOptions($module);
            $inputClass = $this->getInputClass($module);
            $this->formInputs[$inputName] = new $inputClass($options);
        }
        return $this->formInputs[$inputName];
    }

    // This function CANNOT have $props, since we do not always go through initModelProps to set the name of the input
    // Eg: we change the input name through $props 'name' when displaying the form, however in the actionexecuter, it doesn't
    // load that same module (it just accesses directly its value), then it fails retrieving the value since it tries get it from a different field name
    public function getName(array $module): string
    {
        return $this->getModuleHelpers()->getModuleOutputName($module);
    }

    public function getValue(array $module, ?array $source = null): mixed
    {
        return $this->getInput($module)->getValue($source);
    }

    public function isInputSetInSource(array $module, ?array $source = null): mixed
    {
        return $this->getInput($module)->isInputSetInSource($source);
    }

    public function getInputDefaultValue(array $module, array &$props): mixed
    {
        return null;
    }

    public function getDefaultValue(array $module, array &$props): mixed
    {
        $value = $this->getProp($module, $props, 'default-value');
        if (!is_null($value)) {
            return $value;
        }

        return $this->getInputDefaultValue($module, $props);
    }

    public function getInputOptions(array $module): array
    {
        return [
            'name' => $this->getName($module),
        ];
    }
}

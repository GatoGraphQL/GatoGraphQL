<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\FormInputs\FormInput;
use PoP\ComponentModel\FormInputs\FormMultipleInput;

trait FormInputModuleProcessorTrait
{
    // This function CANNOT have $props, since multiple can change the value of the input (eg: from Select to MultiSelect => from '' to array())
    // Yet we do not always go through initModelProps to initialize it, then changing the multiple in the form through $props, and trying to retrieve the value in an actionexecuter will fail
    public function isMultiple(array $module)
    {
        return false;
    }

    public function getInputName(array $module)
    {
        $name = $this->getName($module);
        return $name . ($this->isMultiple($module) ? '[]' : '');
    }

    public function getInputClass(array $module)
    {
        if ($this->isMultiple($module)) {
            return FormMultipleInput::class;
        }

        return FormInput::class;
    }

    public function getInput(array $module)
    {
        $options = $this->getInputOptions($module);
        $input_class = $this->getInputClass($module);
        return new $input_class($options);
    }

    // This function CANNOT have $props, since we do not always go through initModelProps to set the name of the input
    // Eg: we change the input name through $props 'name' when displaying the form, however in the actionexecuter, it doesn't
    // load that same module (it just accesses directly its value), then it fails retrieving the value since it tries get it from a different field name
    public function getName(array $module)
    {
        return ModuleUtils::getModuleOutputName($module);
    }

    public function getValue(array $module, ?array $source = null)
    {
        if ($input = $this->getInput($module)) {
            return $input->getValue($source);
        }
        return null;
    }

    public function getInputDefaultValue(array $module, array &$props)
    {
        return null;
    }

    public function getDefaultValue(array $module, array &$props)
    {
        $value = $this->getProp($module, $props, 'default-value');
        if (!is_null($value)) {
            return $value;
        }

        return $this->getInputDefaultValue($module, $props);
    }

    public function getInputOptions(array $module)
    {
        $options = array(
            'name' => $this->getName($module),
        );

        return $options;
    }
}

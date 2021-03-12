<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

trait FormMultipleInputModuleProcessorTrait
{
    public function getInputSubnames(array $module)
    {
        return array();
    }

    public function getInputOptions(array $module)
    {
        $options = parent::getInputOptions($module);
        $options['subnames'] = $this->getInputSubnames($module);
        return $options;
    }

    public function getInputClass(array $module)
    {
        return \PoP\Engine\GD_FormInput_MultipleInputs::class;
    }

    public function getInputName(array $module)
    {
        // Allow for multiple names, for multiple inputs
        $name = $this->getName($module);
        $names = array();
        foreach ($this->getInputSubnames($module) as $subname) {
            $names[$subname] = \PoP\ComponentModel\PoP_InputUtils::getMultipleinputsName($name, $subname) . ($this->isMultiple($module) ? '[]' : '');
        }
        return $names;
    }
}

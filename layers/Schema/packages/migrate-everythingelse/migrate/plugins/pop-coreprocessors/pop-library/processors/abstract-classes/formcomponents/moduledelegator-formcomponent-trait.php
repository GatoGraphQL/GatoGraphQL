<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

trait FormComponentModuleDelegatorTrait
{
    public function getFormcomponentModule(array $module)
    {
        return null;
    }
    public function getValue(array $module, ?array $source = null)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $formcomponent_module = $this->getFormcomponentModule($module);
        return $moduleprocessor_manager->getProcessor($formcomponent_module)->getValue($formcomponent_module, $source);
    }
    public function getDefaultValue(array $module, array &$props)
    {
        $moduleFullName = ModuleUtils::getModuleFullName($module);
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $formcomponent_module = $this->getFormcomponentModule($module);
        return $moduleprocessor_manager->getProcessor($formcomponent_module)->getDefaultValue($formcomponent_module, $props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES]);
    }
    public function getName(array $module)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $formcomponent_module = $this->getFormcomponentModule($module);
        return $moduleprocessor_manager->getProcessor($formcomponent_module)->getName($formcomponent_module);
    }
    public function getInputName(array $module)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $formcomponent_module = $this->getFormcomponentModule($module);
        return $moduleprocessor_manager->getProcessor($formcomponent_module)->getInputName($formcomponent_module);
    }
    public function isMultiple(array $module)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $formcomponent_module = $this->getFormcomponentModule($module);
        return $moduleprocessor_manager->getProcessor($formcomponent_module)->isMultiple($formcomponent_module);
    }
    public function getLabel(array $module, array &$props)
    {
        $moduleFullName = ModuleUtils::getModuleFullName($module);
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $formcomponent_module = $this->getFormcomponentModule($module);

        // Because getLabel is used on initModelProps, the structure in $props for the submodule may not be created yet, throwing an error since then it's null
        // Just for this case, pass another array, not $props
        $submodule_props = [];
        if ($props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES]) {
            $submodule_props = &$props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES];
        }
        return $moduleprocessor_manager->getProcessor($formcomponent_module)->getLabel($formcomponent_module, $submodule_props);
    }

    public function metaFormcomponentInitModuleRequestProps(array $module, array &$props)
    {
        $formcomponent_module = $this->getFormcomponentModule($module);

        // // Transfer the selected-value
        // $selected_value = $this->getProp($module, $props, 'selected-value');
        // if (!is_null($selected_value)) {

        //     $this->setProp($formcomponent_module, $props, 'selected-value', $selected_value);
        // }

        // Transfer the default-value
        $default_value = $this->getProp($module, $props, 'default-value');
        if (!is_null($default_value)) {
            $this->setProp($formcomponent_module, $props, 'default-value', $default_value);
        }
    }
}

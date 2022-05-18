<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

trait FormComponentModuleDelegatorTrait
{
    public function getFormcomponentModule(array $componentVariation)
    {
        return null;
    }
    public function getValue(array $componentVariation, ?array $source = null): mixed
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_componentVariation = $this->getFormcomponentModule($componentVariation);
        return $componentprocessor_manager->getProcessor($formcomponent_componentVariation)->getValue($formcomponent_componentVariation, $source);
    }
    public function getDefaultValue(array $componentVariation, array &$props): mixed
    {
        $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_componentVariation = $this->getFormcomponentModule($componentVariation);
        return $componentprocessor_manager->getProcessor($formcomponent_componentVariation)->getDefaultValue($formcomponent_componentVariation, $props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES]);
    }
    public function getName(array $componentVariation): string
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_componentVariation = $this->getFormcomponentModule($componentVariation);
        return $componentprocessor_manager->getProcessor($formcomponent_componentVariation)->getName($formcomponent_componentVariation);
    }
    public function getInputName(array $componentVariation): string
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_componentVariation = $this->getFormcomponentModule($componentVariation);
        return $componentprocessor_manager->getProcessor($formcomponent_componentVariation)->getInputName($formcomponent_componentVariation);
    }
    public function isMultiple(array $componentVariation): bool
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_componentVariation = $this->getFormcomponentModule($componentVariation);
        return $componentprocessor_manager->getProcessor($formcomponent_componentVariation)->isMultiple($formcomponent_componentVariation);
    }
    public function getLabel(array $componentVariation, array &$props)
    {
        $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_componentVariation = $this->getFormcomponentModule($componentVariation);

        // Because getLabel is used on initModelProps, the structure in $props for the submodule may not be created yet, throwing an error since then it's null
        // Just for this case, pass another array, not $props
        $submodule_props = [];
        if ($props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES]) {
            $submodule_props = &$props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES];
        }
        return $componentprocessor_manager->getProcessor($formcomponent_componentVariation)->getLabel($formcomponent_componentVariation, $submodule_props);
    }

    public function metaFormcomponentInitModuleRequestProps(array $componentVariation, array &$props)
    {
        $formcomponent_componentVariation = $this->getFormcomponentModule($componentVariation);

        // // Transfer the selected-value
        // $selected_value = $this->getProp($componentVariation, $props, 'selected-value');
        // if (!is_null($selected_value)) {

        //     $this->setProp($formcomponent_componentVariation, $props, 'selected-value', $selected_value);
        // }

        // Transfer the default-value
        $default_value = $this->getProp($componentVariation, $props, 'default-value');
        if (!is_null($default_value)) {
            $this->setProp($formcomponent_componentVariation, $props, 'default-value', $default_value);
        }
    }
}

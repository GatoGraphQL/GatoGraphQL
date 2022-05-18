<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

trait FormComponentModuleDelegatorTrait
{
    public function getFormcomponentModule(array $component)
    {
        return null;
    }
    public function getValue(array $component, ?array $source = null): mixed
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_component = $this->getFormcomponentModule($component);
        return $componentprocessor_manager->getProcessor($formcomponent_component)->getValue($formcomponent_component, $source);
    }
    public function getDefaultValue(array $component, array &$props): mixed
    {
        $componentFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($component);
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_component = $this->getFormcomponentModule($component);
        return $componentprocessor_manager->getProcessor($formcomponent_component)->getDefaultValue($formcomponent_component, $props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS]);
    }
    public function getName(array $component): string
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_component = $this->getFormcomponentModule($component);
        return $componentprocessor_manager->getProcessor($formcomponent_component)->getName($formcomponent_component);
    }
    public function getInputName(array $component): string
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_component = $this->getFormcomponentModule($component);
        return $componentprocessor_manager->getProcessor($formcomponent_component)->getInputName($formcomponent_component);
    }
    public function isMultiple(array $component): bool
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_component = $this->getFormcomponentModule($component);
        return $componentprocessor_manager->getProcessor($formcomponent_component)->isMultiple($formcomponent_component);
    }
    public function getLabel(array $component, array &$props)
    {
        $componentFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($component);
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_component = $this->getFormcomponentModule($component);

        // Because getLabel is used on initModelProps, the structure in $props for the submodule may not be created yet, throwing an error since then it's null
        // Just for this case, pass another array, not $props
        $submodule_props = [];
        if ($props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS]) {
            $submodule_props = &$props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS];
        }
        return $componentprocessor_manager->getProcessor($formcomponent_component)->getLabel($formcomponent_component, $submodule_props);
    }

    public function metaFormcomponentInitModuleRequestProps(array $component, array &$props)
    {
        $formcomponent_component = $this->getFormcomponentModule($component);

        // // Transfer the selected-value
        // $selected_value = $this->getProp($component, $props, 'selected-value');
        // if (!is_null($selected_value)) {

        //     $this->setProp($formcomponent_component, $props, 'selected-value', $selected_value);
        // }

        // Transfer the default-value
        $default_value = $this->getProp($component, $props, 'default-value');
        if (!is_null($default_value)) {
            $this->setProp($formcomponent_component, $props, 'default-value', $default_value);
        }
    }
}

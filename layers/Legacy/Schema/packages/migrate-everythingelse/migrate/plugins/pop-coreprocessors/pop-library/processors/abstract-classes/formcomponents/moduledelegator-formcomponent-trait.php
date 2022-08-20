<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

trait FormComponentModuleDelegatorTrait
{
    public function getFormcomponentComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    /**
     * @param array<string,mixed>|null $source
     */
    public function getValue(\PoP\ComponentModel\Component\Component $component, ?array $source = null): mixed
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_component = $this->getFormcomponentComponent($component);
        return $componentprocessor_manager->getComponentProcessor($formcomponent_component)->getValue($formcomponent_component, $source);
    }
    public function getDefaultValue(\PoP\ComponentModel\Component\Component $component, array &$props): mixed
    {
        $componentFullName = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentFullName($component);
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_component = $this->getFormcomponentComponent($component);
        return $componentprocessor_manager->getComponentProcessor($formcomponent_component)->getDefaultValue($formcomponent_component, $props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS]);
    }
    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_component = $this->getFormcomponentComponent($component);
        return $componentprocessor_manager->getComponentProcessor($formcomponent_component)->getName($formcomponent_component);
    }
    public function getInputName(\PoP\ComponentModel\Component\Component $component): string
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_component = $this->getFormcomponentComponent($component);
        return $componentprocessor_manager->getComponentProcessor($formcomponent_component)->getInputName($formcomponent_component);
    }
    public function isMultiple(\PoP\ComponentModel\Component\Component $component): bool
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_component = $this->getFormcomponentComponent($component);
        return $componentprocessor_manager->getComponentProcessor($formcomponent_component)->isMultiple($formcomponent_component);
    }
    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $componentFullName = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentFullName($component);
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $formcomponent_component = $this->getFormcomponentComponent($component);

        // Because getLabel is used on initModelProps, the structure in $props for the subcomponent may not be created yet, throwing an error since then it's null
        // Just for this case, pass another array, not $props
        $subcomponent_props = [];
        if ($props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS]) {
            $subcomponent_props = &$props[$componentFullName][\PoP\ComponentModel\Constants\Props::SUBCOMPONENTS];
        }
        return $componentprocessor_manager->getComponentProcessor($formcomponent_component)->getLabel($formcomponent_component, $subcomponent_props);
    }

    public function metaFormcomponentInitModuleRequestProps(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $formcomponent_component = $this->getFormcomponentComponent($component);

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

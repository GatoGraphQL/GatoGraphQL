<?php

abstract class PoP_Module_Processor_ShowIfNotEmptyConditionWrapperBase extends PoP_Module_Processor_ConditionWrapperBase
{
    public function getConditionfailedLayoutSubmodules(array $component)
    {
        // The layouts and condition failed layouts are the same, the only difference is adding class "hidden" between the 2 states
        return $this->getLayoutSubmodules($component);
    }

    public function getConditionfailedClass(array $component, array &$props)
    {
        $classs = parent::getConditionfailedClass($component, $props);
        $classs .= ' hidden';

        return $classs;
    }

    public function getTextfieldModule(array $component, array &$props)
    {
        return null;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'pop-show-notempty');
        if ($textfield_component = $this->getTextfieldModule($component, $props)) {
            // Watch out! Class attribute here is called 'textfield-class', so any module implementing the textfield functionality
            // will need to add this class in the span surrounding the data to be refreshed (eg: buttoninner.tmpl)
            $this->appendProp($textfield_component, $props, 'textfield-class', 'pop-show-notempty');
        }
        parent::initModelProps($component, $props);
    }
}

<?php

abstract class PoP_Module_Processor_ShowIfNotEmptyConditionWrapperBase extends PoP_Module_Processor_ConditionWrapperBase
{
    public function getConditionfailedLayoutSubmodules(array $module)
    {
        // The layouts and condition failed layouts are the same, the only difference is adding class "hidden" between the 2 states
        return $this->getLayoutSubmodules($module);
    }

    public function getConditionfailedClass(array $module, array &$props)
    {
        $classs = parent::getConditionfailedClass($module, $props);
        $classs .= ' hidden';

        return $classs;
    }

    public function getTextfieldModule(array $module, array &$props)
    {
        return null;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->appendProp($module, $props, 'class', 'pop-show-notempty');
        if ($textfield_module = $this->getTextfieldModule($module, $props)) {
            // Watch out! Class attribute here is called 'textfield-class', so any module implementing the textfield functionality
            // will need to add this class in the span surrounding the data to be refreshed (eg: buttoninner.tmpl)
            $this->appendProp($textfield_module, $props, 'textfield-class', 'pop-show-notempty');
        }
        parent::initModelProps($module, $props);
    }
}

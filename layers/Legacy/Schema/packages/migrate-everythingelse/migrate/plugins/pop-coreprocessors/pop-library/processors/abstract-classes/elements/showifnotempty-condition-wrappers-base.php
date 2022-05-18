<?php

abstract class PoP_Module_Processor_ShowIfNotEmptyConditionWrapperBase extends PoP_Module_Processor_ConditionWrapperBase
{
    public function getConditionfailedLayoutSubmodules(array $componentVariation)
    {
        // The layouts and condition failed layouts are the same, the only difference is adding class "hidden" between the 2 states
        return $this->getLayoutSubmodules($componentVariation);
    }

    public function getConditionfailedClass(array $componentVariation, array &$props)
    {
        $classs = parent::getConditionfailedClass($componentVariation, $props);
        $classs .= ' hidden';

        return $classs;
    }

    public function getTextfieldModule(array $componentVariation, array &$props)
    {
        return null;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', 'pop-show-notempty');
        if ($textfield_componentVariation = $this->getTextfieldModule($componentVariation, $props)) {
            // Watch out! Class attribute here is called 'textfield-class', so any module implementing the textfield functionality
            // will need to add this class in the span surrounding the data to be refreshed (eg: buttoninner.tmpl)
            $this->appendProp($textfield_componentVariation, $props, 'textfield-class', 'pop-show-notempty');
        }
        parent::initModelProps($componentVariation, $props);
    }
}

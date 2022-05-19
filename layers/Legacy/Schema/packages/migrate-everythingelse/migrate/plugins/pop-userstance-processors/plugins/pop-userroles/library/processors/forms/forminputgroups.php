<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_URE_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT = 'filterinputgroup-authorrole-multiselect';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT],
        );
    }

    public function getComponentSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT:
                return [UserStance_URE_Module_Processor_MultiSelectFilterInputs::class, UserStance_URE_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_AUTHORROLE_MULTISELECT];
        }
        
        return parent::getComponentSubcomponent($component);
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT:
                return TranslationAPIFacade::getInstance()->__('By who:', 'pop-userstance-processors');
        }
        
        return parent::getLabel($component, $props);
    }

    public function getLabelClass(array $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }
}




<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_VOLUNTEERSNEEDED_SELECT = 'forminputgroup-volunteersneeded';
    public final const COMPONENT_FILTERINPUTGROUP_VOLUNTEERSNEEDED_MULTISELECT = 'filterinputgroup-volunteersneededmulti';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUTGROUP_VOLUNTEERSNEEDED_SELECT,
            self::COMPONENT_FILTERINPUTGROUP_VOLUNTEERSNEEDED_MULTISELECT,
        );
    }


    public function getLabelClass(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTGROUP_VOLUNTEERSNEEDED_MULTISELECT:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTGROUP_VOLUNTEERSNEEDED_MULTISELECT:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }
    
    public function getInfo(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUTGROUP_VOLUNTEERSNEEDED_SELECT:
                return TranslationAPIFacade::getInstance()->__('Do you need volunteers? Each time a user applies to volunteer, you will get a notification email with the volunteer\'s contact information.', 'poptheme-wassup');
        }
        
        return parent::getInfo($component, $props);
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTGROUP_VOLUNTEERSNEEDED_MULTISELECT:
                return [PoPTheme_Wassup_Module_Processor_MultiSelectFilterInputs::class, PoPTheme_Wassup_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT];

            case self::COMPONENT_FORMINPUTGROUP_VOLUNTEERSNEEDED_SELECT:
                return [GD_Custom_Module_Processor_SelectFormInputs::class, GD_Custom_Module_Processor_SelectFormInputs::COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT];
        }
        
        return parent::getComponentSubcomponent($component);
    }
}




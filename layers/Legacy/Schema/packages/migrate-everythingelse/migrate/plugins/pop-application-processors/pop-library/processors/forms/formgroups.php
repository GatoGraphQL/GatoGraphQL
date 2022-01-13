<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public const MODULE_FORMINPUTGROUP_VOLUNTEERSNEEDED_SELECT = 'forminputgroup-volunteersneeded';
    public const MODULE_FILTERINPUTGROUP_VOLUNTEERSNEEDED_MULTISELECT = 'filterinputgroup-volunteersneededmulti';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_VOLUNTEERSNEEDED_SELECT],
            [self::class, self::MODULE_FILTERINPUTGROUP_VOLUNTEERSNEEDED_MULTISELECT],
        );
    }


    public function getLabelClass(array $module)
    {
        $ret = parent::getLabelClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUTGROUP_VOLUNTEERSNEEDED_MULTISELECT:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $module)
    {
        $ret = parent::getFormcontrolClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUTGROUP_VOLUNTEERSNEEDED_MULTISELECT:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }
    
    public function getInfo(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_VOLUNTEERSNEEDED_SELECT:
                return TranslationAPIFacade::getInstance()->__('Do you need volunteers? Each time a user applies to volunteer, you will get a notification email with the volunteer\'s contact information.', 'poptheme-wassup');
        }
        
        return parent::getInfo($module, $props);
    }

    public function getComponentSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUTGROUP_VOLUNTEERSNEEDED_MULTISELECT:
                return [PoPTheme_Wassup_Module_Processor_MultiSelectFilterInputs::class, PoPTheme_Wassup_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT];

            case self::MODULE_FORMINPUTGROUP_VOLUNTEERSNEEDED_SELECT:
                return [GD_Custom_Module_Processor_SelectFormInputs::class, GD_Custom_Module_Processor_SelectFormInputs::MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT];
        }
        
        return parent::getComponentSubmodule($module);
    }
}




<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_URE_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT = 'filterinputgroup-authorrole-multiselect';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT:
                return [UserStance_URE_Module_Processor_MultiSelectFilterInputs::class, UserStance_URE_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_AUTHORROLE_MULTISELECT];
        }
        
        return parent::getComponentSubmodule($module);
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT:
                return TranslationAPIFacade::getInstance()->__('By who:', 'pop-userstance-processors');
        }
        
        return parent::getLabel($module, $props);
    }

    public function getLabelClass(array $module)
    {
        $ret = parent::getLabelClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $module)
    {
        $ret = parent::getFormcontrolClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUTGROUP_AUTHORROLE_MULTISELECT:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }
}




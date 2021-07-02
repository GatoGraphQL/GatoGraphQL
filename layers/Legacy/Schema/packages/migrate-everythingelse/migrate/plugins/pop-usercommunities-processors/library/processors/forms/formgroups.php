<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_URE_Module_Processor_ProfileFormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public const MODULE_URE_FORMINPUTGROUP_MEMBERPRIVILEGES = 'ure-forminputgroup-memberprivileges';
    public const MODULE_URE_FORMINPUTGROUP_MEMBERTAGS = 'ure-forminputgroup-membertags';
    public const MODULE_URE_FORMINPUTGROUP_MEMBERSTATUS = 'ure-forminputgroup-memberstatus';
    public const MODULE_URE_FILTERINPUTGROUP_MEMBERPRIVILEGES = 'ure-filterinputgroup-memberprivileges';
    public const MODULE_URE_FILTERINPUTGROUP_MEMBERTAGS = 'ure-filterinputgroup-membertags';
    public const MODULE_URE_FILTERINPUTGROUP_MEMBERSTATUS = 'ure-filterinputgroup-memberstatus';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUTGROUP_MEMBERPRIVILEGES],
            [self::class, self::MODULE_URE_FORMINPUTGROUP_MEMBERTAGS],
            [self::class, self::MODULE_URE_FORMINPUTGROUP_MEMBERSTATUS],
            [self::class, self::MODULE_URE_FILTERINPUTGROUP_MEMBERPRIVILEGES],
            [self::class, self::MODULE_URE_FILTERINPUTGROUP_MEMBERTAGS],
            [self::class, self::MODULE_URE_FILTERINPUTGROUP_MEMBERSTATUS],
        );
    }

    public function getLabelClass(array $module)
    {
        $ret = parent::getLabelClass($module);

        switch ($module[1]) {
            case self::MODULE_URE_FILTERINPUTGROUP_MEMBERPRIVILEGES:
            case self::MODULE_URE_FILTERINPUTGROUP_MEMBERTAGS:
            case self::MODULE_URE_FILTERINPUTGROUP_MEMBERSTATUS:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $module)
    {
        $ret = parent::getFormcontrolClass($module);

        switch ($module[1]) {
            case self::MODULE_URE_FILTERINPUTGROUP_MEMBERPRIVILEGES:
            case self::MODULE_URE_FILTERINPUTGROUP_MEMBERTAGS:
            case self::MODULE_URE_FILTERINPUTGROUP_MEMBERSTATUS:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_URE_FORMINPUTGROUP_MEMBERPRIVILEGES => [GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::MODULE_URE_FORMINPUT_MEMBERPRIVILEGES],
            self::MODULE_URE_FORMINPUTGROUP_MEMBERTAGS => [GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::MODULE_URE_FORMINPUT_MEMBERTAGS],
            self::MODULE_URE_FORMINPUTGROUP_MEMBERSTATUS => [GD_URE_Module_Processor_SelectFormInputs::class, GD_URE_Module_Processor_SelectFormInputs::MODULE_URE_FORMINPUT_MEMBERSTATUS],
            self::MODULE_URE_FILTERINPUTGROUP_MEMBERPRIVILEGES => [GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES],
            self::MODULE_URE_FILTERINPUTGROUP_MEMBERTAGS => [GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::MODULE_URE_FILTERINPUT_MEMBERTAGS],
            self::MODULE_URE_FILTERINPUTGROUP_MEMBERSTATUS => [GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFilterInputs::MODULE_URE_FILTERINPUT_MEMBERSTATUS],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }

    public function getInfo(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUTGROUP_MEMBERSTATUS:
                return TranslationAPIFacade::getInstance()->__('Status "Active" if the user is truly your member, or "Rejected" otherwise. Rejected users will not appear as your community\'s members, or contribute content.');

            case self::MODULE_URE_FORMINPUTGROUP_MEMBERPRIVILEGES:
                return TranslationAPIFacade::getInstance()->__('"Contribute content" will add the member\'s content to your profile.', 'ure-popprocessors');

            case self::MODULE_URE_FORMINPUTGROUP_MEMBERTAGS:
                return TranslationAPIFacade::getInstance()->__('What is the type of relationship from this member to your community.', 'ure-popprocessors');
        }

        return parent::getInfo($module, $props);
    }
}




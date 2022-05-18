<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_CommonUserRoles_UserCommunities_Module_Processor_ProfileFormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_URE_FORMINPUTGROUP_CUP_ISCOMMUNITY = 'ure-forminputgroup-cup-iscommunity';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUTGROUP_CUP_ISCOMMUNITY],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_URE_FORMINPUTGROUP_CUP_ISCOMMUNITY => [GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs::class, GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs::MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }

    public function getInfo(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUTGROUP_CUP_ISCOMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Become a Community: all the content posted by your members will also appear under your Organization\'s profile.');
        }

        return parent::getInfo($module, $props);
    }
}




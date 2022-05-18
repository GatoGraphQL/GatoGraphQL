<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_CommonUserRoles_UserCommunities_Module_Processor_ProfileFormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_URE_FORMINPUTGROUP_CUP_ISCOMMUNITY = 'ure-forminputgroup-cup-iscommunity';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_FORMINPUTGROUP_CUP_ISCOMMUNITY],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_URE_FORMINPUTGROUP_CUP_ISCOMMUNITY => [GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs::class, GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs::COMPONENT_URE_FORMINPUT_CUP_ISCOMMUNITY],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }

    public function getInfo(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUTGROUP_CUP_ISCOMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Become a Community: all the content posted by your members will also appear under your Organization\'s profile.');
        }

        return parent::getInfo($component, $props);
    }
}




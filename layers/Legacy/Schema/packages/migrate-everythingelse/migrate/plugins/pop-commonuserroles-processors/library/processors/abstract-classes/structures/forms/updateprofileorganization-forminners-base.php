<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class GD_URE_Module_Processor_UpdateProfileOrganizationFormInnersBase extends PoP_Module_Processor_UpdateProfileFormInnersBase
{
    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        // Add common Create/Update components
        PoP_Module_Processor_CreateUpdateProfileOrganizationFormsUtils::getFormSubmodules($module, $ret, $this);

        if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
            // Add extra components
            array_splice(
                $ret,
                array_search(
                    [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_DESCRIPTION],
                    $ret
                )+1,
                0,
                [
                    [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::MODULE_DIVIDER],
                    [GD_CommonUserRoles_UserCommunities_Module_Processor_ProfileFormGroups::class, GD_CommonUserRoles_UserCommunities_Module_Processor_ProfileFormGroups::MODULE_URE_FORMINPUTGROUP_CUP_ISCOMMUNITY],
                ]
            );
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Change the label
        $this->setProp([PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_FIRSTNAME], $props, 'label', TranslationAPIFacade::getInstance()->__('Organization Name*', 'ure-popprocessors'));
        $this->setProp([PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_EMAIL], $props, 'label', TranslationAPIFacade::getInstance()->__('Organization Email*', 'ure-popprocessors'));

        parent::initModelProps($module, $props);
    }
}

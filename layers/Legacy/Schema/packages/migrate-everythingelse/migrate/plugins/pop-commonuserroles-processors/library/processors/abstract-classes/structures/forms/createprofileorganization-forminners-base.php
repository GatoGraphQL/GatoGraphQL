<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class GD_URE_Module_Processor_CreateProfileOrganizationFormInnersBase extends PoP_Module_Processor_CreateProfileFormInnersBase
{
    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        // Add common Create/Update components
        PoP_Module_Processor_CreatProfileFormsUtils::getFormSubmodules($componentVariation, $ret, $this);
        PoP_Module_Processor_CreateUpdateProfileOrganizationFormsUtils::getFormSubmodules($componentVariation, $ret, $this);

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
                    [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::MODULE_COLLAPSIBLEDIVIDER],
                    [GD_CommonUserRoles_UserCommunities_Module_Processor_ProfileFormGroups::class, GD_CommonUserRoles_UserCommunities_Module_Processor_ProfileFormGroups::MODULE_URE_FORMINPUTGROUP_CUP_ISCOMMUNITY],
                ]
            );
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Change the label
        $this->setProp([PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_FIRSTNAME], $props, 'label', TranslationAPIFacade::getInstance()->__('Organization Name*', 'ure-popprocessors'));
        $this->setProp([PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_EMAIL], $props, 'label', TranslationAPIFacade::getInstance()->__('Organization Email*', 'ure-popprocessors'));

        // Make it a Community by default
        $this->setProp([GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs::class, GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs::MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY], $props, 'default-value', true);

        parent::initModelProps($componentVariation, $props);
    }
}

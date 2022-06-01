<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class GD_URE_Module_Processor_CreateProfileOrganizationFormInnersBase extends PoP_Module_Processor_CreateProfileFormInnersBase
{
    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        // Add common Create/Update components
        PoP_Module_Processor_CreatProfileFormsUtils::getFormSubcomponents($component, $ret, $this);
        PoP_Module_Processor_CreateUpdateProfileOrganizationFormsUtils::getFormSubcomponents($component, $ret, $this);

        if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
            // Add extra components
            array_splice(
                $ret,
                array_search(
                    [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_DESCRIPTION],
                    $ret
                )+1,
                0,
                [
                    [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::COMPONENT_COLLAPSIBLEDIVIDER],
                    [GD_CommonUserRoles_UserCommunities_Module_Processor_ProfileFormGroups::class, GD_CommonUserRoles_UserCommunities_Module_Processor_ProfileFormGroups::COMPONENT_URE_FORMINPUTGROUP_CUP_ISCOMMUNITY],
                ]
            );
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {

        // Change the label
        $this->setProp([PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_FIRSTNAME], $props, 'label', TranslationAPIFacade::getInstance()->__('Organization Name*', 'ure-popprocessors'));
        $this->setProp([PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_EMAIL], $props, 'label', TranslationAPIFacade::getInstance()->__('Organization Email*', 'ure-popprocessors'));

        // Make it a Community by default
        $this->setProp([GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs::class, GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs::COMPONENT_URE_FORMINPUT_CUP_ISCOMMUNITY], $props, 'default-value', true);

        parent::initModelProps($component, $props);
    }
}

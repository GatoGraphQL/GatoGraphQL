<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Newsletter_GF_CreateUpdate_Profile_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter('gd_createupdate_profile:form_data', array($this, 'getFormData'), 10);
        \PoP\Root\App::getHookManager()->addFilter('pop_module:createprofile:components', array($this, 'getComponentSubmodules'), 10, 3);
        \PoP\Root\App::getHookManager()->addAction('gd_createupdate_profile:additionalsCreate', array($this, 'additionals'), 10, 1);
    }

    public function enabled()
    {

        // By default it is not enabled
        return \PoP\Root\App::getHookManager()->applyFilters(
            'GD_GF_CreateUpdate_Profile_Hooks:enabled',
            false
        );
    }

    public function getFormData($form_data)
    {
        if (!$this->enabled()) {
            return $form_data;
        }

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $form_data['newsletter'] = $moduleprocessor_manager->getProcessor([GenericForms_Module_Processor_CheckboxFormInputs::class, GenericForms_Module_Processor_CheckboxFormInputs::MODULE_FORMINPUT_CUP_NEWSLETTER])->getValue([GenericForms_Module_Processor_CheckboxFormInputs::class, GenericForms_Module_Processor_CheckboxFormInputs::MODULE_FORMINPUT_CUP_NEWSLETTER]);
        return $form_data;
    }

    public function getComponentSubmodules($components, array $module, $processor)
    {
        if (!$this->enabled()) {
            return $components;
        }

        // Add After the last divider (after User URL)
        array_splice(
            $components, 
            array_search(
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_USERWEBSITEURL], 
                $components
            )+2, 
            0, 
            array(
                [PoP_Newsletter_Module_Processor_NoLabelFormComponentGroups::class, PoP_Newsletter_Module_Processor_NoLabelFormComponentGroups::MODULE_FORMINPUTGROUP_CUP_NEWSLETTER],
            )
        );
        
        return $components;
    }

    public function additionals($user_id)
    {
        if (!$this->enabled()) {
            return;
        }

        $subscribe = $form_data['newsletter'];
        if ($subscribe) {
            // Trigger the form sending
            $form_id = PoP_Newsletter_GFHelpers::getNewsletterFormId();
            $fieldnames = PoP_Newsletter_GFHelpers::getNewsletterFormFieldNames();

            $userTypeAPI = UserTypeAPIFacade::getInstance();
            $form_values = array(
                $fieldnames['email'] => $userTypeAPI->getUserEmail($user_id),
                $fieldnames['name'] => $userTypeAPI->getUserDisplayName($user_id),
            );

            // Taken from http://www.gravityhelp.com/documentation/gravity-forms/extending-gravity-forms/api/api-functions/#submit_form
            GFAPI::submit_form($form_id, $form_values);
        }
    }
}

/**
 * Initialize
 */
new PoP_Newsletter_GF_CreateUpdate_Profile_Hooks();

<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Newsletter_GF_CreateUpdate_Profile_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addAction('gd_createupdate_profile:form_data', $this->appendMutationDataToFieldDataProvider(...), 10);
        \PoP\Root\App::addFilter('pop_component:createprofile:components', $this->getComponentSubcomponents(...), 10, 3);
        \PoP\Root\App::addAction('gd_createupdate_profile:additionalsCreate', $this->additionalsCreate(...), 10, 2);
    }

    public function enabled()
    {

        // By default it is not enabled
        return \PoP\Root\App::applyFilters(
            'GD_GF_CreateUpdate_Profile_Hooks:enabled',
            false
        );
    }

    public function appendMutationDataToFieldDataProvider(\PoP\ComponentModel\Mutation\FieldDataProviderInterface $fieldDataProvider): void
    {
        if (!$this->enabled()) {
            return;
        }

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $fieldDataProvider->add('newsletter', $componentprocessor_manager->getComponentProcessor([GenericForms_Module_Processor_CheckboxFormInputs::class, GenericForms_Module_Processor_CheckboxFormInputs::COMPONENT_FORMINPUT_CUP_NEWSLETTER])->getValue([GenericForms_Module_Processor_CheckboxFormInputs::class, GenericForms_Module_Processor_CheckboxFormInputs::COMPONENT_FORMINPUT_CUP_NEWSLETTER]));
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getComponentSubcomponents(array $components, \PoP\ComponentModel\Component\Component $component, $processor): array
    {
        if (!$this->enabled()) {
            return $components;
        }

        // Add After the last divider (after User URL)
        array_splice(
            $components, 
            array_search(
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_USERWEBSITEURL], 
                $components
            )+2, 
            0, 
            array(
                [PoP_Newsletter_Module_Processor_NoLabelFormComponentGroups::class, PoP_Newsletter_Module_Processor_NoLabelFormComponentGroups::COMPONENT_FORMINPUTGROUP_CUP_NEWSLETTER],
            )
        );
        
        return $components;
    }

    public function additionalsCreate($user_id, WithArgumentsInterface $withArgumentsAST)
    {
        if (!$this->enabled()) {
            return;
        }

        $subscribe = $withArgumentsAST->getArgumentValue('newsletter');
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

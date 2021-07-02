<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class PoP_Newsletter_GFHelpers
{
    public static function getNewsletterFormFieldNames()
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $gfinputname_modules = array(
            POP_GENERICFORMS_GF_FORM_NEWSLETTER_FIELDNAME_EMAIL_ID => [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAIL],
            POP_GENERICFORMS_GF_FORM_NEWSLETTER_FIELDNAME_NAME_ID => [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTERNAME],
        );
        $fieldnames = array();
        foreach ($gfinputname_modules as $gf_field_name => $module) {
            $fieldnames[$moduleprocessor_manager->getProcessor($module)->getName($module)] = $gf_field_name;
        }
        
        return $fieldnames;
    }

    public static function getNewsletterFormId()
    {
        return POP_GENERICFORMS_GF_FORM_NEWSLETTER_FORM_ID;
    }
}

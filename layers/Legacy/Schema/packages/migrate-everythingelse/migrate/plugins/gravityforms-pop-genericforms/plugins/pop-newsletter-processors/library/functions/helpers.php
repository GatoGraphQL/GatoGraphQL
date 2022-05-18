<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoP_Newsletter_GFHelpers
{
    public static function getNewsletterFormFieldNames()
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $gfinputname_modules = array(
            POP_GENERICFORMS_GF_FORM_NEWSLETTER_FIELDNAME_EMAIL_ID => [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAIL],
            POP_GENERICFORMS_GF_FORM_NEWSLETTER_FIELDNAME_NAME_ID => [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTERNAME],
        );
        $fieldnames = array();
        foreach ($gfinputname_modules as $gf_field_name => $componentVariation) {
            $fieldnames[$componentprocessor_manager->getProcessor($componentVariation)->getName($componentVariation)] = $gf_field_name;
        }
        
        return $fieldnames;
    }

    public static function getNewsletterFormId()
    {
        return POP_GENERICFORMS_GF_FORM_NEWSLETTER_FORM_ID;
    }
}

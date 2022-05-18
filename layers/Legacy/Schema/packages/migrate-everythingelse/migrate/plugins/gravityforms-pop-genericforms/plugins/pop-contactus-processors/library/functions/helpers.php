<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoP_ContactUs_GFHelpers
{
    public static function getContactusFormFieldNames()
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $gfinputname_modules = array(
            POP_GENERICFORMS_GF_FORM_CONTACTUS_FIELDNAME_NAME_ID => [PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME],
            POP_GENERICFORMS_GF_FORM_CONTACTUS_FIELDNAME_EMAIL_ID => [PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL],
            POP_GENERICFORMS_GF_FORM_CONTACTUS_FIELDNAME_TOPIC_ID => [GenericForms_Module_Processor_SelectFormInputs::class, GenericForms_Module_Processor_SelectFormInputs::MODULE_FORMINPUT_TOPIC],
            POP_GENERICFORMS_GF_FORM_CONTACTUS_FIELDNAME_SUBJECT_ID => [PoP_ContactUs_Module_Processor_TextFormInputs::class, PoP_ContactUs_Module_Processor_TextFormInputs::MODULE_FORMINPUT_SUBJECT],
            POP_GENERICFORMS_GF_FORM_CONTACTUS_FIELDNAME_MESSAGE_ID => [PoP_ContactUs_Module_Processor_TextareaFormInputs::class, PoP_ContactUs_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_MESSAGE],
        );
        $fieldnames = array();
        foreach ($gfinputname_modules as $gf_field_name => $componentVariation) {
            $fieldnames[$componentprocessor_manager->getProcessor($componentVariation)->getName($componentVariation)] = $gf_field_name;
        }

        return $fieldnames;
    }

    public static function getContactusFormId()
    {
        return POP_GENERICFORMS_GF_FORM_CONTACTUS_FORM_ID;
    }
}

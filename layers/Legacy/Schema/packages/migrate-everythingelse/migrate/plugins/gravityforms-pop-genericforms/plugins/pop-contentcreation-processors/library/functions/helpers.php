<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoP_ContentCreation_GFHelpers
{
    public static function getFlagFormFieldNames()
    {
        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $gfinputname_modules = array(
            POP_GENERICFORMS_GF_FORM_FLAG_FIELDNAME_NAME_ID => [PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME],
            POP_GENERICFORMS_GF_FORM_FLAG_FIELDNAME_EMAIL_ID => [PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL],
            POP_GENERICFORMS_GF_FORM_FLAG_FIELDNAME_WHYFLAG_ID => [PoP_ContentCreation_Module_Processor_TextareaFormInputs::class, PoP_ContentCreation_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_WHYFLAG],
            POP_GENERICFORMS_GF_FORM_FLAG_FIELDNAME_PAGEURL_ID => [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_TARGETURL],
            POP_GENERICFORMS_GF_FORM_FLAG_FIELDNAME_PAGETITLE_ID => [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_POSTTITLE],
        );
        $fieldnames = array();
        foreach ($gfinputname_modules as $gf_field_name => $module) {
            $fieldnames[$moduleprocessor_manager->getProcessor($module)->getName($module)] = $gf_field_name;
        }
        
        return $fieldnames;
    }

    public static function getFlagFormId()
    {
        return POP_GENERICFORMS_GF_FORM_FLAG_FORM_ID;
    }
}

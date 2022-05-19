<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoP_Share_GFHelpers
{
    public static function getSharebyemailFormFieldNames()
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $gfinputname_components = array(
            POP_GENERICFORMS_GF_FORM_SHAREBYEMAIL_FIELDNAME_NAME_ID => [PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME],
            POP_GENERICFORMS_GF_FORM_SHAREBYEMAIL_FIELDNAME_DESTINATIONEMAILS_ID => [PoP_Share_Module_Processor_TextFormInputs::class, PoP_Share_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_DESTINATIONEMAIL],
            POP_GENERICFORMS_GF_FORM_SHAREBYEMAIL_FIELDNAME_ADDITIONALMESSAGE_ID => [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_ADDITIONALMESSAGE],
            POP_GENERICFORMS_GF_FORM_SHAREBYEMAIL_FIELDNAME_PAGEURL_ID => [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETURL],
            POP_GENERICFORMS_GF_FORM_SHAREBYEMAIL_FIELDNAME_PAGETITLE_ID => [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETTITLE],
        );
        $fieldnames = array();
        foreach ($gfinputname_components as $gf_field_name => $component) {
            $fieldnames[$componentprocessor_manager->getProcessor($component)->getName($component)] = $gf_field_name;
        }
        
        return $fieldnames;
    }

    public static function getSharebyemailFormId()
    {
        return POP_GENERICFORMS_GF_FORM_SHAREBYEMAIL_FORM_ID;
    }
}

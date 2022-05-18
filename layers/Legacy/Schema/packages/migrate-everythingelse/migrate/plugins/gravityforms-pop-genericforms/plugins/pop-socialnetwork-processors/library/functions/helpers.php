<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoP_SocialNetwork_GFHelpers
{
    public static function getContactuserFormFieldNames()
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $gfinputname_components = array(
            POP_GENERICFORMS_GF_FORM_CONTACTUSER_FIELDNAME_NAME_ID => [PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME],
            POP_GENERICFORMS_GF_FORM_CONTACTUSER_FIELDNAME_EMAIL_ID => [PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL],
            POP_GENERICFORMS_GF_FORM_CONTACTUSER_FIELDNAME_SUBJECT_ID => [PoP_SocialNetwork_Module_Processor_TextFormInputs::class, PoP_SocialNetwork_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_MESSAGESUBJECT],
            POP_GENERICFORMS_GF_FORM_CONTACTUSER_FIELDNAME_MESSAGE_ID => [PoP_SocialNetwork_Module_Processor_TextareaFormInputs::class, PoP_SocialNetwork_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_MESSAGETOUSER],
            POP_GENERICFORMS_GF_FORM_CONTACTUSER_FIELDNAME_PAGEURL_ID => [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_TARGETURL],
            POP_GENERICFORMS_GF_FORM_CONTACTUSER_FIELDNAME_PAGETITLE_ID => [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_USERNICENAME],
        );
        $fieldnames = array();
        foreach ($gfinputname_components as $gf_field_name => $component) {
            $fieldnames[$componentprocessor_manager->getProcessor($component)->getName($component)] = $gf_field_name;
        }
        
        return $fieldnames;
    }

    public static function getContactuserFormId()
    {
        return POP_GENERICFORMS_GF_FORM_CONTACTUSER_FORM_ID;
    }
}

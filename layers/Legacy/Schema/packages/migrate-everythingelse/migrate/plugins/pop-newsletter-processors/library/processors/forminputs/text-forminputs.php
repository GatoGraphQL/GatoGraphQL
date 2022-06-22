<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Newsletter_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const COMPONENT_FORMINPUT_NEWSLETTERNAME = 'forminput-newslettername';
    public final const COMPONENT_FORMINPUT_NEWSLETTEREMAIL = 'forminput-newsletteremail';
    public final const COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL = 'forminput-newsletteremailverificationemail';
    public final const COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE = 'forminput-newsletteremailverificationcode';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_NEWSLETTERNAME,
            self::COMPONENT_FORMINPUT_NEWSLETTEREMAIL,
            self::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL,
            self::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_NEWSLETTERNAME:
                return TranslationAPIFacade::getInstance()->__('Your Name', 'pop-genericforms');
            
            case self::COMPONENT_FORMINPUT_NEWSLETTEREMAIL:
            case self::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
                return TranslationAPIFacade::getInstance()->__('Your Email', 'pop-genericforms');

            case self::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return TranslationAPIFacade::getInstance()->__('Verification code', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_NEWSLETTEREMAIL:
            case self::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
            case self::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }

    public function isHidden(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return true;
        }
        
        return parent::isHidden($component, $props);
    }

    // function getName(\PoP\ComponentModel\Component\Component $component) {
    
    //     switch ($component->name) {
        
    //         case self::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
            
    //             return 'email';
        
    //         case self::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
            
    //             return 'code';
    //     }
        
    //     return parent::getName($component);
    // }

    public function clearInput(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_NEWSLETTERNAME:
            case self::COMPONENT_FORMINPUT_NEWSLETTEREMAIL:
            case self::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
            case self::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return true;
        }

        return parent::clearInput($component, $props);
    }
}




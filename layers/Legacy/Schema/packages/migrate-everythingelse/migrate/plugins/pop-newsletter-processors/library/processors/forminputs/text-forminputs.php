<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Newsletter_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_NEWSLETTERNAME = 'forminput-newslettername';
    public final const MODULE_FORMINPUT_NEWSLETTEREMAIL = 'forminput-newsletteremail';
    public final const MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL = 'forminput-newsletteremailverificationemail';
    public final const MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE = 'forminput-newsletteremailverificationcode';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_NEWSLETTERNAME],
            [self::class, self::MODULE_FORMINPUT_NEWSLETTEREMAIL],
            [self::class, self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL],
            [self::class, self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_NEWSLETTERNAME:
                return TranslationAPIFacade::getInstance()->__('Your Name', 'pop-genericforms');
            
            case self::MODULE_FORMINPUT_NEWSLETTEREMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
                return TranslationAPIFacade::getInstance()->__('Your Email', 'pop-genericforms');

            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return TranslationAPIFacade::getInstance()->__('Verification code', 'pop-genericforms');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_NEWSLETTEREMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }

    public function isHidden(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return true;
        }
        
        return parent::isHidden($component, $props);
    }

    // function getName(array $component) {
    
    //     switch ($component[1]) {
        
    //         case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
            
    //             return 'email';
        
    //         case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
            
    //             return 'code';
    //     }
        
    //     return parent::getName($component);
    // }

    public function clearInput(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_NEWSLETTERNAME:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return true;
        }

        return parent::clearInput($component, $props);
    }
}




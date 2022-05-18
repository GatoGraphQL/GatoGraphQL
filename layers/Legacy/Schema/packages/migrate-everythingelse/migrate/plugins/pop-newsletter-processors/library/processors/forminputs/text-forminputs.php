<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Newsletter_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_NEWSLETTERNAME = 'forminput-newslettername';
    public final const MODULE_FORMINPUT_NEWSLETTEREMAIL = 'forminput-newsletteremail';
    public final const MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL = 'forminput-newsletteremailverificationemail';
    public final const MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE = 'forminput-newsletteremailverificationcode';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_NEWSLETTERNAME],
            [self::class, self::MODULE_FORMINPUT_NEWSLETTEREMAIL],
            [self::class, self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL],
            [self::class, self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_NEWSLETTERNAME:
                return TranslationAPIFacade::getInstance()->__('Your Name', 'pop-genericforms');
            
            case self::MODULE_FORMINPUT_NEWSLETTEREMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
                return TranslationAPIFacade::getInstance()->__('Your Email', 'pop-genericforms');

            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return TranslationAPIFacade::getInstance()->__('Verification code', 'pop-genericforms');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function isMandatory(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_NEWSLETTEREMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return true;
        }
        
        return parent::isMandatory($componentVariation, $props);
    }

    public function isHidden(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return true;
        }
        
        return parent::isHidden($componentVariation, $props);
    }

    // function getName(array $componentVariation) {
    
    //     switch ($componentVariation[1]) {
        
    //         case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
            
    //             return 'email';
        
    //         case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
            
    //             return 'code';
    //     }
        
    //     return parent::getName($componentVariation);
    // }

    public function clearInput(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_NEWSLETTERNAME:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return true;
        }

        return parent::clearInput($componentVariation, $props);
    }
}




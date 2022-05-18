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

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_NEWSLETTERNAME:
                return TranslationAPIFacade::getInstance()->__('Your Name', 'pop-genericforms');
            
            case self::MODULE_FORMINPUT_NEWSLETTEREMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
                return TranslationAPIFacade::getInstance()->__('Your Email', 'pop-genericforms');

            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return TranslationAPIFacade::getInstance()->__('Verification code', 'pop-genericforms');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function isMandatory(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_NEWSLETTEREMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return true;
        }
        
        return parent::isMandatory($module, $props);
    }

    public function isHidden(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return true;
        }
        
        return parent::isHidden($module, $props);
    }

    // function getName(array $module) {
    
    //     switch ($module[1]) {
        
    //         case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
            
    //             return 'email';
        
    //         case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
            
    //             return 'code';
    //     }
        
    //     return parent::getName($module);
    // }

    public function clearInput(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_NEWSLETTERNAME:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL:
            case self::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE:
                return true;
        }

        return parent::clearInput($module, $props);
    }
}




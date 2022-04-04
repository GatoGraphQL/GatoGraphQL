<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Volunteering_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_PHONE = 'gf-field-phone';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_PHONE],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_PHONE:
                return TranslationAPIFacade::getInstance()->__('Your Phone number', 'pop-genericforms');
        }
        
        return parent::getLabelText($module, $props);
    }
}




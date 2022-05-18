<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_MESSAGESUBJECT = 'gf-field-messagesubject';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_MESSAGESUBJECT],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_MESSAGESUBJECT:
                return  TranslationAPIFacade::getInstance()->__('Subject', 'pop-genericforms');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function clearInput(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_MESSAGESUBJECT:
                return true;
        }

        return parent::clearInput($module, $props);
    }
}




<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Volunteering_Module_Processor_TextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public final const MODULE_FORMINPUT_WHYVOLUNTEER = 'gf-field-whyvolunteer';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_WHYVOLUNTEER],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_WHYVOLUNTEER:
                return TranslationAPIFacade::getInstance()->__('Why do you want to volunteer?', 'pop-genericforms');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function isMandatory(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_WHYVOLUNTEER:
                return true;
        }
        
        return parent::isMandatory($module, $props);
    }

    public function clearInput(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_WHYVOLUNTEER:
                return true;
        }

        return parent::clearInput($module, $props);
    }
}




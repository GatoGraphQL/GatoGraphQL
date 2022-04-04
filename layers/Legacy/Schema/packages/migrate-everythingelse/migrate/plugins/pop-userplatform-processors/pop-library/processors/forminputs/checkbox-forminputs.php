<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdateProfileCheckboxFormInputs extends PoP_Module_Processor_BooleanCheckboxFormInputsBase
{
    public final const MODULE_FORMINPUT_CUP_DISPLAYEMAIL = 'forminput-cup-displayemail';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_CUP_DISPLAYEMAIL],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_DISPLAYEMAIL:
                return TranslationAPIFacade::getInstance()->__('Show email in your user profile?', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_DISPLAYEMAIL:
                return 'displayEmail';
        }

        return parent::getDbobjectField($module);
    }
}




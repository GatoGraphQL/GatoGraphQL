<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GenericForms_Module_Processor_CheckboxFormInputs extends PoP_Module_Processor_BooleanCheckboxFormInputsBase
{
    public const MODULE_FORMINPUT_CUP_NEWSLETTER = 'gf-cup-newsletter';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_CUP_NEWSLETTER],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_NEWSLETTER:
                return TranslationAPIFacade::getInstance()->__('Subscribe to our Newsletter?', 'pop-genericforms');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getInputDefaultValue(array $module, array &$props): mixed
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_NEWSLETTER:
                // Subscribe to newsletter by default
                return true;
        }
        
        return parent::getInputDefaultValue($module, $props);
    }
}




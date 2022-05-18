<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GenericForms_Module_Processor_SelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public final const MODULE_FORMINPUT_TOPIC = 'gf-field-topic';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_TOPIC],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TOPIC:
                return TranslationAPIFacade::getInstance()->__('Topic', 'pop-genericforms');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TOPIC:
                return GD_FormInput_ContactUs_Topics::class;
        }
        
        return parent::getInputClass($module);
    }
}




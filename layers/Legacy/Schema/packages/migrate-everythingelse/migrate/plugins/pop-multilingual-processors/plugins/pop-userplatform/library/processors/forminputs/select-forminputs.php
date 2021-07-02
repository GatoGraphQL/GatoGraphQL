<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_QT_Module_Processor_SelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public const MODULE_QT_FORMINPUT_LANGUAGE = 'qt-forminput-language';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_QT_FORMINPUT_LANGUAGE],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_QT_FORMINPUT_LANGUAGE:
                return TranslationAPIFacade::getInstance()->__('Language', 'poptheme-wassup');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_QT_FORMINPUT_LANGUAGE:
                return GD_QT_FormInput_Languages::class;
        }
        
        return parent::getInputClass($module);
    }
}




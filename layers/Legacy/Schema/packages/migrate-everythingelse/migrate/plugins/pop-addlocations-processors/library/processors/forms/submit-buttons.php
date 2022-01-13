<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public const MODULE_EM_SUBMITBUTTON_ADDLOCATION = 'em-submitbutton-addlocation';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_SUBMITBUTTON_ADDLOCATION],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_EM_SUBMITBUTTON_ADDLOCATION:
                return TranslationAPIFacade::getInstance()->__('Add Location', 'em-popprocessors');
        }

        return parent::getLabel($module, $props);
    }

    public function getLoadingText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_EM_SUBMITBUTTON_ADDLOCATION:
                return TranslationAPIFacade::getInstance()->__('Adding Location...', 'pop-coreprocessors');
        }
        
        return parent::getLoadingText($module, $props);
    }
}



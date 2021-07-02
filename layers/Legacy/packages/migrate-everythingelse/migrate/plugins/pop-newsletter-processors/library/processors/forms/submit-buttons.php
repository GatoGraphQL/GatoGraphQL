<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_Newsletter_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public const MODULE_GF_SUBMITBUTTON_SUBSCRIBE = 'gf-submitbutton-subscribe';
    public const MODULE_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION = 'gf-submitbutton-confirmunsubscription';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GF_SUBMITBUTTON_SUBSCRIBE],
            [self::class, self::MODULE_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_GF_SUBMITBUTTON_SUBSCRIBE:
                return TranslationAPIFacade::getInstance()->__('Subscribe', 'pop-genericforms');

            case self::MODULE_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION:
                return TranslationAPIFacade::getInstance()->__('Confirm unsubscription', 'pop-genericforms');
        }

        return parent::getLabel($module, $props);
    }
    
    public function getBtnClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_GF_SUBMITBUTTON_SUBSCRIBE:
            case self::MODULE_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION:
                return 'btn btn-info';
        }

        return parent::getBtnClass($module, $props);
    }

    public function getLoadingText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_GF_SUBMITBUTTON_SUBSCRIBE:
            case self::MODULE_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION:
                return TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms');
        }
        
        return parent::getLoadingText($module, $props);
    }
}



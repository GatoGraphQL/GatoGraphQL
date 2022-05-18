<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Newsletter_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public final const MODULE_GF_SUBMITBUTTON_SUBSCRIBE = 'gf-submitbutton-subscribe';
    public final const MODULE_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION = 'gf-submitbutton-confirmunsubscription';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GF_SUBMITBUTTON_SUBSCRIBE],
            [self::class, self::MODULE_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION],
        );
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_GF_SUBMITBUTTON_SUBSCRIBE:
                return TranslationAPIFacade::getInstance()->__('Subscribe', 'pop-genericforms');

            case self::MODULE_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION:
                return TranslationAPIFacade::getInstance()->__('Confirm unsubscription', 'pop-genericforms');
        }

        return parent::getLabel($componentVariation, $props);
    }
    
    public function getBtnClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_GF_SUBMITBUTTON_SUBSCRIBE:
            case self::MODULE_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION:
                return 'btn btn-info';
        }

        return parent::getBtnClass($componentVariation, $props);
    }

    public function getLoadingText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_GF_SUBMITBUTTON_SUBSCRIBE:
            case self::MODULE_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION:
                return TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms');
        }
        
        return parent::getLoadingText($componentVariation, $props);
    }
}



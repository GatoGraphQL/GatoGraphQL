<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Newsletter_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public final const COMPONENT_GF_SUBMITBUTTON_SUBSCRIBE = 'gf-submitbutton-subscribe';
    public final const COMPONENT_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION = 'gf-submitbutton-confirmunsubscription';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_GF_SUBMITBUTTON_SUBSCRIBE,
            self::COMPONENT_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION,
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_GF_SUBMITBUTTON_SUBSCRIBE:
                return TranslationAPIFacade::getInstance()->__('Subscribe', 'pop-genericforms');

            case self::COMPONENT_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION:
                return TranslationAPIFacade::getInstance()->__('Confirm unsubscription', 'pop-genericforms');
        }

        return parent::getLabel($component, $props);
    }
    
    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_GF_SUBMITBUTTON_SUBSCRIBE:
            case self::COMPONENT_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION:
                return 'btn btn-info';
        }

        return parent::getBtnClass($component, $props);
    }

    public function getLoadingText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_GF_SUBMITBUTTON_SUBSCRIBE:
            case self::COMPONENT_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION:
                return TranslationAPIFacade::getInstance()->__('Sending...', 'pop-genericforms');
        }
        
        return parent::getLoadingText($component, $props);
    }
}



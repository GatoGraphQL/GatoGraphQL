<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_EM_BUTTONINNER_GOOGLECALENDAR = 'em-buttoninner-googlecalendar';
    public final const COMPONENT_EM_BUTTONINNER_ICAL = 'em-buttoninner-ical';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_BUTTONINNER_GOOGLECALENDAR,
            self::COMPONENT_EM_BUTTONINNER_ICAL,
        );
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_BUTTONINNER_GOOGLECALENDAR:
                return 'fa-fw fa-thumb-tack';
            
            case self::COMPONENT_EM_BUTTONINNER_ICAL:
                return 'fa-fw fa-download';
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_BUTTONINNER_GOOGLECALENDAR:
                return TranslationAPIFacade::getInstance()->__('Google Calendar', 'em-popprocessors');
            
            case self::COMPONENT_EM_BUTTONINNER_ICAL:
                return TranslationAPIFacade::getInstance()->__('iCal', 'em-popprocessors');
        }

        return parent::getBtnTitle($component);
    }
}



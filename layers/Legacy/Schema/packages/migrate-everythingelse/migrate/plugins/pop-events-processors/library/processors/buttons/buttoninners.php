<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public const MODULE_EM_BUTTONINNER_GOOGLECALENDAR = 'em-buttoninner-googlecalendar';
    public const MODULE_EM_BUTTONINNER_ICAL = 'em-buttoninner-ical';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_BUTTONINNER_GOOGLECALENDAR],
            [self::class, self::MODULE_EM_BUTTONINNER_ICAL],
        );
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_EM_BUTTONINNER_GOOGLECALENDAR:
                return 'fa-fw fa-thumb-tack';
            
            case self::MODULE_EM_BUTTONINNER_ICAL:
                return 'fa-fw fa-download';
        }

        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_EM_BUTTONINNER_GOOGLECALENDAR:
                return TranslationAPIFacade::getInstance()->__('Google Calendar', 'em-popprocessors');
            
            case self::MODULE_EM_BUTTONINNER_ICAL:
                return TranslationAPIFacade::getInstance()->__('iCal', 'em-popprocessors');
        }

        return parent::getBtnTitle($module);
    }
}



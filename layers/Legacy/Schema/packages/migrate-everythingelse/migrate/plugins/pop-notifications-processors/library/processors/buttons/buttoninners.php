<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class AAL_PoPProcessors_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_AAL_BUTTONINNER_NOTIFICATIONPREVIEWLINK = 'notifications-buttoninner-notificationpreviewlink';
    public final const COMPONENT_AAL_BUTTONINNER_USERVIEW = 'notifications-buttoninner-userview';
    public final const COMPONENT_AAL_BUTTONINNER_NOTIFICATION_MARKASREAD = 'notifications-buttoninner-notification-markasread';
    public final const COMPONENT_AAL_BUTTONINNER_NOTIFICATION_MARKASUNREAD = 'notifications-buttoninner-notification-markasunread';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_AAL_BUTTONINNER_NOTIFICATIONPREVIEWLINK],
            [self::class, self::COMPONENT_AAL_BUTTONINNER_USERVIEW],
            [self::class, self::COMPONENT_AAL_BUTTONINNER_NOTIFICATION_MARKASREAD],
            [self::class, self::COMPONENT_AAL_BUTTONINNER_NOTIFICATION_MARKASUNREAD],
        );
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_AAL_BUTTONINNER_USERVIEW:
                return 'fa-fw fa-eye';

            case self::COMPONENT_AAL_BUTTONINNER_NOTIFICATION_MARKASREAD:
                return 'fa-fw fa-circle-o';

            case self::COMPONENT_AAL_BUTTONINNER_NOTIFICATION_MARKASUNREAD:
                return 'fa-fw fa-circle';
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_AAL_BUTTONINNER_USERVIEW:
                return TranslationAPIFacade::getInstance()->__('View', 'pop-notifications-processors');
        }

        return parent::getBtnTitle($component);
    }
}



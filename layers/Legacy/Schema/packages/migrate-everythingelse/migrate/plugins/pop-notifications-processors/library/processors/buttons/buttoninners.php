<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class AAL_PoPProcessors_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public const MODULE_AAL_BUTTONINNER_NOTIFICATIONPREVIEWLINK = 'notifications-buttoninner-notificationpreviewlink';
    public const MODULE_AAL_BUTTONINNER_USERVIEW = 'notifications-buttoninner-userview';
    public const MODULE_AAL_BUTTONINNER_NOTIFICATION_MARKASREAD = 'notifications-buttoninner-notification-markasread';
    public const MODULE_AAL_BUTTONINNER_NOTIFICATION_MARKASUNREAD = 'notifications-buttoninner-notification-markasunread';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_AAL_BUTTONINNER_NOTIFICATIONPREVIEWLINK],
            [self::class, self::MODULE_AAL_BUTTONINNER_USERVIEW],
            [self::class, self::MODULE_AAL_BUTTONINNER_NOTIFICATION_MARKASREAD],
            [self::class, self::MODULE_AAL_BUTTONINNER_NOTIFICATION_MARKASUNREAD],
        );
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_AAL_BUTTONINNER_USERVIEW:
                return 'fa-fw fa-eye';

            case self::MODULE_AAL_BUTTONINNER_NOTIFICATION_MARKASREAD:
                return 'fa-fw fa-circle-o';

            case self::MODULE_AAL_BUTTONINNER_NOTIFICATION_MARKASUNREAD:
                return 'fa-fw fa-circle';
        }

        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_AAL_BUTTONINNER_USERVIEW:
                return TranslationAPIFacade::getInstance()->__('View', 'pop-notifications-processors');
        }

        return parent::getBtnTitle($module);
    }
}



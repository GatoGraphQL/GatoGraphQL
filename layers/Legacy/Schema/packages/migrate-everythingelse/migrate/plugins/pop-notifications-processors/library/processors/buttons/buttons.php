<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class AAL_PoPProcessors_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const MODULE_AAL_BUTTON_NOTIFICATIONPREVIEWLINK = 'notifications-button-notificationpreviewlink';
    public final const MODULE_AAL_BUTTON_USERVIEW = 'notifications-button-userview';
    public final const MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD = 'notifications-button-notification-markasread';
    public final const MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD = 'notifications-button-notification-markasunread';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_AAL_BUTTON_NOTIFICATIONPREVIEWLINK],
            [self::class, self::MODULE_AAL_BUTTON_USERVIEW],
            [self::class, self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD],
            [self::class, self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD],
        );
    }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_AAL_BUTTON_NOTIFICATIONPREVIEWLINK:
                return [AAL_PoPProcessors_Module_Processor_ButtonInners::class, AAL_PoPProcessors_Module_Processor_ButtonInners::MODULE_AAL_BUTTONINNER_NOTIFICATIONPREVIEWLINK];

            case self::MODULE_AAL_BUTTON_USERVIEW:
                return [AAL_PoPProcessors_Module_Processor_ButtonInners::class, AAL_PoPProcessors_Module_Processor_ButtonInners::MODULE_AAL_BUTTONINNER_USERVIEW];

            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD:
                return [AAL_PoPProcessors_Module_Processor_ButtonInners::class, AAL_PoPProcessors_Module_Processor_ButtonInners::MODULE_AAL_BUTTONINNER_NOTIFICATION_MARKASREAD];

            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                return [AAL_PoPProcessors_Module_Processor_ButtonInners::class, AAL_PoPProcessors_Module_Processor_ButtonInners::MODULE_AAL_BUTTONINNER_NOTIFICATION_MARKASUNREAD];
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }
    public function getLinktargetField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_AAL_BUTTON_NOTIFICATIONPREVIEWLINK:
                return 'target';
        }

        return parent::getLinktargetField($componentVariation);
    }

    public function getUrlField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_AAL_BUTTON_NOTIFICATIONPREVIEWLINK:
                return 'url';

            case self::MODULE_AAL_BUTTON_USERVIEW:
                return 'websiteURL';

            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD:
                return 'markAsReadURL';

            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                return 'markAsUnreadURL';
        }

        return parent::getUrlField($componentVariation);
    }

    public function showTooltip(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD:
            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                return true;
        }

        return parent::showTooltip($componentVariation, $props);
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_AAL_BUTTON_USERVIEW:
                return TranslationAPIFacade::getInstance()->__('View', 'pop-notifications-processors');

            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD:
                return TranslationAPIFacade::getInstance()->__('Mark as read', 'pop-notifications-processors');

            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                return TranslationAPIFacade::getInstance()->__('Mark as unread', 'pop-notifications-processors');
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_AAL_BUTTON_NOTIFICATIONPREVIEWLINK:
                $ret .= ' url';
                break;

            case self::MODULE_AAL_BUTTON_USERVIEW:
                $ret .= ' btn btn-xs btn-default';
                break;

            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD:
            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                $ret .= ' btn btn-xs btn-link';
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD:
                $ret .= ' pop-functional '.AAL_CLASS_NOTIFICATION_MARKASREAD;
                break;

            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                $ret .= ' pop-functional '.AAL_CLASS_NOTIFICATION_MARKASUNREAD;
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD:
            case self::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                // Tell the Search engines to not follow the link
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'rel' => 'nofollow',
                    )
                );
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



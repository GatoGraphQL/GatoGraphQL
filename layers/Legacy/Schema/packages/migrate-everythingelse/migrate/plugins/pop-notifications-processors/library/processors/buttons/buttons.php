<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class AAL_PoPProcessors_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const MODULE_AAL_BUTTON_NOTIFICATIONPREVIEWLINK = 'notifications-button-notificationpreviewlink';
    public final const MODULE_AAL_BUTTON_USERVIEW = 'notifications-button-userview';
    public final const MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD = 'notifications-button-notification-markasread';
    public final const MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD = 'notifications-button-notification-markasunread';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_AAL_BUTTON_NOTIFICATIONPREVIEWLINK],
            [self::class, self::COMPONENT_AAL_BUTTON_USERVIEW],
            [self::class, self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD],
            [self::class, self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASUNREAD],
        );
    }

    public function getButtoninnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_AAL_BUTTON_NOTIFICATIONPREVIEWLINK:
                return [AAL_PoPProcessors_Module_Processor_ButtonInners::class, AAL_PoPProcessors_Module_Processor_ButtonInners::COMPONENT_AAL_BUTTONINNER_NOTIFICATIONPREVIEWLINK];

            case self::COMPONENT_AAL_BUTTON_USERVIEW:
                return [AAL_PoPProcessors_Module_Processor_ButtonInners::class, AAL_PoPProcessors_Module_Processor_ButtonInners::COMPONENT_AAL_BUTTONINNER_USERVIEW];

            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD:
                return [AAL_PoPProcessors_Module_Processor_ButtonInners::class, AAL_PoPProcessors_Module_Processor_ButtonInners::COMPONENT_AAL_BUTTONINNER_NOTIFICATION_MARKASREAD];

            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                return [AAL_PoPProcessors_Module_Processor_ButtonInners::class, AAL_PoPProcessors_Module_Processor_ButtonInners::COMPONENT_AAL_BUTTONINNER_NOTIFICATION_MARKASUNREAD];
        }

        return parent::getButtoninnerSubmodule($component);
    }
    public function getLinktargetField(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_AAL_BUTTON_NOTIFICATIONPREVIEWLINK:
                return 'target';
        }

        return parent::getLinktargetField($component);
    }

    public function getUrlField(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_AAL_BUTTON_NOTIFICATIONPREVIEWLINK:
                return 'url';

            case self::COMPONENT_AAL_BUTTON_USERVIEW:
                return 'websiteURL';

            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD:
                return 'markAsReadURL';

            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                return 'markAsUnreadURL';
        }

        return parent::getUrlField($component);
    }

    public function showTooltip(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD:
            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                return true;
        }

        return parent::showTooltip($component, $props);
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_AAL_BUTTON_USERVIEW:
                return TranslationAPIFacade::getInstance()->__('View', 'pop-notifications-processors');

            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD:
                return TranslationAPIFacade::getInstance()->__('Mark as read', 'pop-notifications-processors');

            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                return TranslationAPIFacade::getInstance()->__('Mark as unread', 'pop-notifications-processors');
        }

        return parent::getTitle($component, $props);
    }

    public function getBtnClass(array $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_AAL_BUTTON_NOTIFICATIONPREVIEWLINK:
                $ret .= ' url';
                break;

            case self::COMPONENT_AAL_BUTTON_USERVIEW:
                $ret .= ' btn btn-xs btn-default';
                break;

            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD:
            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                $ret .= ' btn btn-xs btn-link';
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD:
                $ret .= ' pop-functional '.AAL_CLASS_NOTIFICATION_MARKASREAD;
                break;

            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                $ret .= ' pop-functional '.AAL_CLASS_NOTIFICATION_MARKASUNREAD;
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD:
            case self::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASUNREAD:
                // Tell the Search engines to not follow the link
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'rel' => 'nofollow',
                    )
                );
                break;
        }

        parent::initModelProps($component, $props);
    }
}



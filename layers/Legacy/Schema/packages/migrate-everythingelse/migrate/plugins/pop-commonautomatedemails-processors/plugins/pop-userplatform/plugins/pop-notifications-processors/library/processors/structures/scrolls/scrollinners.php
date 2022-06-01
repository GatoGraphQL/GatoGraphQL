<?php

class GD_AAL_Module_Processor_AutomatedEmailsScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS = 'scrollinner-automatedemails-notifications-details';
    public final const COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_LIST = 'scrollinner-automatedemails-notifications-list';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS,
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_LIST,
        );
    }

    public function getLayoutGrid(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS:
            case self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_LIST:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS => [PoP_Module_Processor_AutomatedEmailsPreviewNotificationLayouts::class, PoP_Module_Processor_AutomatedEmailsPreviewNotificationLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_DETAILS],
            self::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_LIST => [PoP_Module_Processor_AutomatedEmailsPreviewNotificationLayouts::class, PoP_Module_Processor_AutomatedEmailsPreviewNotificationLayouts::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_LIST],
        );
        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



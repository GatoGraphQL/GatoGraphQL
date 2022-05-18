<?php

class GD_AAL_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_NOTIFICATIONS_DETAILS = 'scrollinner-notifications-details';
    public final const MODULE_SCROLLINNER_NOTIFICATIONS_LIST = 'scrollinner-notifications-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_NOTIFICATIONS_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_NOTIFICATIONS_LIST],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_SCROLLINNER_NOTIFICATIONS_DETAILS:
            case self::MODULE_SCROLLINNER_NOTIFICATIONS_LIST:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::MODULE_SCROLLINNER_NOTIFICATIONS_DETAILS => [PoP_Module_Processor_PreviewNotificationLayouts::class, PoP_Module_Processor_PreviewNotificationLayouts::MODULE_LAYOUT_PREVIEWNOTIFICATION_DETAILS],
            self::MODULE_SCROLLINNER_NOTIFICATIONS_LIST => [PoP_Module_Processor_PreviewNotificationLayouts::class, PoP_Module_Processor_PreviewNotificationLayouts::MODULE_LAYOUT_PREVIEWNOTIFICATION_LIST],
        );
        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



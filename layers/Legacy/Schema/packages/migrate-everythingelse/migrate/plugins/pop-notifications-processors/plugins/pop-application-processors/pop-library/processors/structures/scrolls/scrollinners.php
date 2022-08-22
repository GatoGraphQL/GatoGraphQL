<?php

class GD_AAL_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const COMPONENT_SCROLLINNER_NOTIFICATIONS_DETAILS = 'scrollinner-notifications-details';
    public final const COMPONENT_SCROLLINNER_NOTIFICATIONS_LIST = 'scrollinner-notifications-list';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCROLLINNER_NOTIFICATIONS_DETAILS,
            self::COMPONENT_SCROLLINNER_NOTIFICATIONS_LIST,
        );
    }

    public function getLayoutGrid(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SCROLLINNER_NOTIFICATIONS_DETAILS:
            case self::COMPONENT_SCROLLINNER_NOTIFICATIONS_LIST:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_SCROLLINNER_NOTIFICATIONS_DETAILS => [PoP_Module_Processor_PreviewNotificationLayouts::class, PoP_Module_Processor_PreviewNotificationLayouts::COMPONENT_LAYOUT_PREVIEWNOTIFICATION_DETAILS],
            self::COMPONENT_SCROLLINNER_NOTIFICATIONS_LIST => [PoP_Module_Processor_PreviewNotificationLayouts::class, PoP_Module_Processor_PreviewNotificationLayouts::COMPONENT_LAYOUT_PREVIEWNOTIFICATION_LIST],
        );
        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



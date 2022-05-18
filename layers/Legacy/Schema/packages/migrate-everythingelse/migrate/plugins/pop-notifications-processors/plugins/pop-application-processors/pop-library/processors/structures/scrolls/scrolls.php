<?php

class GD_AAL_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_NOTIFICATIONS_DETAILS = 'scroll-notifications-details';
    public final const MODULE_SCROLL_NOTIFICATIONS_LIST = 'scroll-notifications-list';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_NOTIFICATIONS_DETAILS],
            [self::class, self::MODULE_SCROLL_NOTIFICATIONS_LIST],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_NOTIFICATIONS_DETAILS => [GD_AAL_Module_Processor_CustomScrollInners::class, GD_AAL_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_NOTIFICATIONS_DETAILS],
            self::MODULE_SCROLL_NOTIFICATIONS_LIST => [GD_AAL_Module_Processor_CustomScrollInners::class, GD_AAL_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_NOTIFICATIONS_LIST],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Extra classes
        $lists = array(
            [self::class, self::MODULE_SCROLL_NOTIFICATIONS_LIST],
        );
        $details = array(
            [self::class, self::MODULE_SCROLL_NOTIFICATIONS_DETAILS],
        );

        $extra_class = '';
        if (in_array($module, $details)) {
            $extra_class = 'details';
        } elseif (in_array($module, $lists)) {
            $extra_class = 'list';
        }
        $this->appendProp($module, $props, 'class', $extra_class);

        switch ($module[1]) {
            case self::MODULE_SCROLL_NOTIFICATIONS_DETAILS:
            case self::MODULE_SCROLL_NOTIFICATIONS_LIST:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($module, $props, 'resourceloader', 'scroll-notifications');
                $this->appendProp($module, $props, 'class', 'scroll-notifications');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



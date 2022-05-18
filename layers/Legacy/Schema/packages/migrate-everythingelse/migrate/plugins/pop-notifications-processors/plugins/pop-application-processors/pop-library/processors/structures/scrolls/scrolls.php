<?php

class GD_AAL_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_NOTIFICATIONS_DETAILS = 'scroll-notifications-details';
    public final const MODULE_SCROLL_NOTIFICATIONS_LIST = 'scroll-notifications-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLL_NOTIFICATIONS_DETAILS],
            [self::class, self::COMPONENT_SCROLL_NOTIFICATIONS_LIST],
        );
    }


    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_NOTIFICATIONS_DETAILS => [GD_AAL_Module_Processor_CustomScrollInners::class, GD_AAL_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_NOTIFICATIONS_DETAILS],
            self::COMPONENT_SCROLL_NOTIFICATIONS_LIST => [GD_AAL_Module_Processor_CustomScrollInners::class, GD_AAL_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_NOTIFICATIONS_LIST],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Extra classes
        $lists = array(
            [self::class, self::COMPONENT_SCROLL_NOTIFICATIONS_LIST],
        );
        $details = array(
            [self::class, self::COMPONENT_SCROLL_NOTIFICATIONS_DETAILS],
        );

        $extra_class = '';
        if (in_array($component, $details)) {
            $extra_class = 'details';
        } elseif (in_array($component, $lists)) {
            $extra_class = 'list';
        }
        $this->appendProp($component, $props, 'class', $extra_class);

        switch ($component[1]) {
            case self::COMPONENT_SCROLL_NOTIFICATIONS_DETAILS:
            case self::COMPONENT_SCROLL_NOTIFICATIONS_LIST:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($component, $props, 'resourceloader', 'scroll-notifications');
                $this->appendProp($component, $props, 'class', 'scroll-notifications');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



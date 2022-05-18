<?php

class GD_AAL_Module_Processor_AutomatedEmailsScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS = 'scroll-automatedemails-notifications-details';
    public final const MODULE_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_LIST = 'scroll-automatedemails-notifications-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS],
            [self::class, self::COMPONENT_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_LIST],
        );
    }


    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS => [GD_AAL_Module_Processor_AutomatedEmailsScrollInners::class, GD_AAL_Module_Processor_AutomatedEmailsScrollInners::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS],
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_LIST => [GD_AAL_Module_Processor_AutomatedEmailsScrollInners::class, GD_AAL_Module_Processor_AutomatedEmailsScrollInners::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_NOTIFICATIONS_LIST],
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
            [self::class, self::COMPONENT_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_LIST],
        );
        $details = array(
            [self::class, self::COMPONENT_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS],
        );

        $extra_class = '';
        if (in_array($component, $details)) {
            $extra_class = 'details';
        } elseif (in_array($component, $lists)) {
            $extra_class = 'list';
        }
        $this->appendProp($component, $props, 'class', $extra_class);

        switch ($component[1]) {
            case self::COMPONENT_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_DETAILS:
            case self::COMPONENT_SCROLL_AUTOMATEDEMAILS_NOTIFICATIONS_LIST:
                $this->appendProp($component, $props, 'class', 'scroll-notifications');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



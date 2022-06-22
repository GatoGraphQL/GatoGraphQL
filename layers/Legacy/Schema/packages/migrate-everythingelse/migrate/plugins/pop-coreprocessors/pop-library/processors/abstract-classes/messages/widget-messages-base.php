<?php

abstract class PoP_Module_Processor_WidgetMessagesBase extends PoP_Module_Processor_MessagesBase
{
    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'widgetmessage text-warning');
        parent::initModelProps($component, $props);
    }
}

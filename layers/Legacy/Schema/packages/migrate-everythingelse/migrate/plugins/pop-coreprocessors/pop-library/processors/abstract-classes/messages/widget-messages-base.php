<?php

abstract class PoP_Module_Processor_WidgetMessagesBase extends PoP_Module_Processor_MessagesBase
{
    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', 'widgetmessage text-warning');
        parent::initModelProps($componentVariation, $props);
    }
}

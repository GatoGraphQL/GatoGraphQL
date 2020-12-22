<?php

abstract class PoP_Module_Processor_WidgetMessagesBase extends PoP_Module_Processor_MessagesBase
{
    public function initModelProps(array $module, array &$props)
    {
        $this->appendProp($module, $props, 'class', 'widgetmessage text-warning');
        parent::initModelProps($module, $props);
    }
}

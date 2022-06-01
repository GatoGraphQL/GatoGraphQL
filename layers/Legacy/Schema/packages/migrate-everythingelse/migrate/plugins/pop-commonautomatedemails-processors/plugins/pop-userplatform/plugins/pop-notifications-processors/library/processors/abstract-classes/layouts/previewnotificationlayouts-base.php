<?php

abstract class PoP_Module_Processor_AutomatedEmailsPreviewNotificationLayoutsBase extends PoP_Module_Processor_PreviewNotificationLayoutsBase
{
    public function getQuicklinkgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function getLinkSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function addUrlLink(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return true;
    }
}

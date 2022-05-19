<?php

abstract class PoP_Module_Processor_AutomatedEmailsPreviewNotificationLayoutsBase extends PoP_Module_Processor_PreviewNotificationLayoutsBase
{
    public function getQuicklinkgroupTopSubcomponent(array $component)
    {
        return null;
    }
    public function getLinkSubcomponent(array $component)
    {
        return null;
    }
    public function addUrlLink(array $component, array &$props)
    {
        return true;
    }
}

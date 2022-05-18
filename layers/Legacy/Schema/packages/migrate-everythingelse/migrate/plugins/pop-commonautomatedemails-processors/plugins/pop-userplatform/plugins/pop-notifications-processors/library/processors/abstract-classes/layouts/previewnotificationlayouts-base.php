<?php

abstract class PoP_Module_Processor_AutomatedEmailsPreviewNotificationLayoutsBase extends PoP_Module_Processor_PreviewNotificationLayoutsBase
{
    public function getQuicklinkgroupTopSubmodule(array $componentVariation)
    {
        return null;
    }
    public function getLinkSubmodule(array $componentVariation)
    {
        return null;
    }
    public function addUrlLink(array $componentVariation, array &$props)
    {
        return true;
    }
}

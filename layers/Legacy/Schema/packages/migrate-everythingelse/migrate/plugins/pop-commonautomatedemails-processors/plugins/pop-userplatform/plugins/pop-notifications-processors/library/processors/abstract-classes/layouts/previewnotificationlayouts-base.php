<?php

abstract class PoP_Module_Processor_AutomatedEmailsPreviewNotificationLayoutsBase extends PoP_Module_Processor_PreviewNotificationLayoutsBase
{
    public function getQuicklinkgroupTopSubmodule(array $module)
    {
        return null;
    }
    public function getLinkSubmodule(array $module)
    {
        return null;
    }
    public function addUrlLink(array $module, array &$props)
    {
        return true;
    }
}

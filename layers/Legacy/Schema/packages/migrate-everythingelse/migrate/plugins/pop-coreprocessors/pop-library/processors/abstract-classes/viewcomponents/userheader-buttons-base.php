<?php

abstract class PoP_Module_Processor_UserHeaderViewComponentButtonsBase extends PoP_Module_Processor_UserViewComponentButtonsBase
{
    public function getHeaderSubmodule(array $module): ?array
    {
        if ($this->headerShowUrl($module)) {
            return [PoP_Module_Processor_UserViewComponentHeaders::class, PoP_Module_Processor_UserViewComponentHeaders::MODULE_VIEWCOMPONENT_HEADER_USER_URL];
        }

        return [PoP_Module_Processor_UserViewComponentHeaders::class, PoP_Module_Processor_UserViewComponentHeaders::MODULE_VIEWCOMPONENT_HEADER_USER];
    }
}

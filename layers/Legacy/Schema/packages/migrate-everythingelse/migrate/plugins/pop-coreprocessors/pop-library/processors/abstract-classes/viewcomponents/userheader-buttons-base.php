<?php

abstract class PoP_Module_Processor_UserHeaderViewComponentButtonsBase extends PoP_Module_Processor_UserViewComponentButtonsBase
{
    public function getHeaderSubcomponent(\PoP\ComponentModel\Component\Component $component): ?array
    {
        if ($this->headerShowUrl($component)) {
            return [PoP_Module_Processor_UserViewComponentHeaders::class, PoP_Module_Processor_UserViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_USER_URL];
        }

        return [PoP_Module_Processor_UserViewComponentHeaders::class, PoP_Module_Processor_UserViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_USER];
    }
}

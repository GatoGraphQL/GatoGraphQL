<?php

abstract class PoP_Module_Processor_PostHeaderViewComponentButtonsBase extends PoP_Module_Processor_PostViewComponentButtonsBase
{
    public function getHeaderSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        if ($this->headerShowUrl($component)) {
            return [PoP_Module_Processor_PostViewComponentHeaders::class, PoP_Module_Processor_PostViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_POST_URL];
        }

        return [PoP_Module_Processor_PostViewComponentHeaders::class, PoP_Module_Processor_PostViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_POST];
    }
}

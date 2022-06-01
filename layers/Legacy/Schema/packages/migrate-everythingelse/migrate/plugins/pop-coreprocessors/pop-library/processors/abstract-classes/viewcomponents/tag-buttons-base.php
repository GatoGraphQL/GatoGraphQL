<?php

abstract class PoP_Module_Processor_TagViewComponentButtonsBase extends PoP_Module_Processor_ViewComponentButtonsBase
{
    public function getHeaderSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        if ($this->headerShowUrl($component)) {
            return [PoP_Module_Processor_TagViewComponentHeaders::class, PoP_Module_Processor_TagViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_TAG_URL];
        }

        return [PoP_Module_Processor_TagViewComponentHeaders::class, PoP_Module_Processor_TagViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_TAG];
    }

    public function getDbobjectParams(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getDbobjectParams($component);

        $ret['data-target-title'] = 'name';
        $ret['data-target-url'] = 'url';

        return $ret;
    }
}

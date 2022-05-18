<?php

abstract class PoP_Module_Processor_TagViewComponentButtonsBase extends PoP_Module_Processor_ViewComponentButtonsBase
{
    public function getHeaderSubmodule(array $component): ?array
    {
        if ($this->headerShowUrl($component)) {
            return [PoP_Module_Processor_TagViewComponentHeaders::class, PoP_Module_Processor_TagViewComponentHeaders::MODULE_VIEWCOMPONENT_HEADER_TAG_URL];
        }

        return [PoP_Module_Processor_TagViewComponentHeaders::class, PoP_Module_Processor_TagViewComponentHeaders::MODULE_VIEWCOMPONENT_HEADER_TAG];
    }

    public function getDbobjectParams(array $component): array
    {
        $ret = parent::getDbobjectParams($component);

        $ret['data-target-title'] = 'name';
        $ret['data-target-url'] = 'url';

        return $ret;
    }
}

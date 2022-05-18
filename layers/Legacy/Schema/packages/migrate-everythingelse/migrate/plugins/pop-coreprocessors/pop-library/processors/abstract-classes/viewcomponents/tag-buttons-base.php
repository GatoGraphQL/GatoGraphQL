<?php

abstract class PoP_Module_Processor_TagViewComponentButtonsBase extends PoP_Module_Processor_ViewComponentButtonsBase
{
    public function getHeaderSubmodule(array $componentVariation): ?array
    {
        if ($this->headerShowUrl($componentVariation)) {
            return [PoP_Module_Processor_TagViewComponentHeaders::class, PoP_Module_Processor_TagViewComponentHeaders::MODULE_VIEWCOMPONENT_HEADER_TAG_URL];
        }

        return [PoP_Module_Processor_TagViewComponentHeaders::class, PoP_Module_Processor_TagViewComponentHeaders::MODULE_VIEWCOMPONENT_HEADER_TAG];
    }

    public function getDbobjectParams(array $componentVariation): array
    {
        $ret = parent::getDbobjectParams($componentVariation);

        $ret['data-target-title'] = 'name';
        $ret['data-target-url'] = 'url';

        return $ret;
    }
}

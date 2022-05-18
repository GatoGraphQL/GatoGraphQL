<?php

class PoP_Module_Processor_TagViewComponentHeaders extends PoP_Module_Processor_TagViewComponentHeadersBase
{
    public final const MODULE_VIEWCOMPONENT_HEADER_TAG = 'viewcomponent-header-tag';
    public final const MODULE_VIEWCOMPONENT_HEADER_TAG_URL = 'viewcomponent-header-tag-url';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_HEADER_TAG],
            [self::class, self::MODULE_VIEWCOMPONENT_HEADER_TAG_URL],
        );
    }

    public function headerShowUrl(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_HEADER_TAG_URL:
                // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
                return true;
        }

        return parent::headerShowUrl($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_HEADER_TAG_URL:
                $this->appendProp($componentVariation, $props, 'class', 'alert alert-warning alert-sm');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



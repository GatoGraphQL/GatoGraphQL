<?php

class PoP_Module_Processor_TagViewComponentHeaders extends PoP_Module_Processor_TagViewComponentHeadersBase
{
    public final const MODULE_VIEWCOMPONENT_HEADER_TAG = 'viewcomponent-header-tag';
    public final const MODULE_VIEWCOMPONENT_HEADER_TAG_URL = 'viewcomponent-header-tag-url';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_HEADER_TAG],
            [self::class, self::COMPONENT_VIEWCOMPONENT_HEADER_TAG_URL],
        );
    }

    public function headerShowUrl(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_HEADER_TAG_URL:
                // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
                return true;
        }

        return parent::headerShowUrl($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_HEADER_TAG_URL:
                $this->appendProp($component, $props, 'class', 'alert alert-warning alert-sm');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



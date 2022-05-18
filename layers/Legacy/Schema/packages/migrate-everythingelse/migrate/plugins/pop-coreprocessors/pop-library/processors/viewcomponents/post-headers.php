<?php

class PoP_Module_Processor_PostViewComponentHeaders extends PoP_Module_Processor_PostViewComponentHeadersBase
{
    public final const MODULE_VIEWCOMPONENT_HEADER_POST = 'viewcomponent-header-post-';
    public final const MODULE_VIEWCOMPONENT_HEADER_POST_URL = 'viewcomponent-header-post-url';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_HEADER_POST],
            [self::class, self::COMPONENT_VIEWCOMPONENT_HEADER_POST_URL],
        );
    }

    public function headerShowUrl(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_HEADER_POST_URL:
                return true;
        }

        return parent::headerShowUrl($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_HEADER_POST_URL:
                $this->appendProp($component, $props, 'class', 'alert alert-warning alert-sm');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



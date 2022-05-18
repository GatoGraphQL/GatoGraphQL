<?php

class PoP_Module_Processor_CommentViewComponentHeaders extends PoP_Module_Processor_CommentViewComponentHeadersBase
{
    public final const MODULE_VIEWCOMPONENT_HEADER_COMMENTPOST = 'viewcomponent-header-commentpost';
    public final const MODULE_VIEWCOMPONENT_HEADER_COMMENTPOST_URL = 'viewcomponent-header-commentpost-url';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_HEADER_COMMENTPOST],
            [self::class, self::COMPONENT_VIEWCOMPONENT_HEADER_COMMENTPOST_URL],
        );
    }

    public function getHeaderSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_HEADER_COMMENTPOST:
                return [PoP_Module_Processor_PostViewComponentHeaders::class, PoP_Module_Processor_PostViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_POST];

            case self::COMPONENT_VIEWCOMPONENT_HEADER_COMMENTPOST_URL:
                return [PoP_Module_Processor_PostViewComponentHeaders::class, PoP_Module_Processor_PostViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_POST_URL];
        }

        return parent::getHeaderSubmodule($component);
    }

    public function headerShowUrl(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_HEADER_COMMENTPOST_URL:
                return true;
        }

        return parent::headerShowUrl($component, $props);
    }
}



<?php

class PoP_Module_Processor_CommentViewComponentHeaders extends PoP_Module_Processor_CommentViewComponentHeadersBase
{
    public final const MODULE_VIEWCOMPONENT_HEADER_COMMENTPOST = 'viewcomponent-header-commentpost';
    public final const MODULE_VIEWCOMPONENT_HEADER_COMMENTPOST_URL = 'viewcomponent-header-commentpost-url';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_HEADER_COMMENTPOST],
            [self::class, self::MODULE_VIEWCOMPONENT_HEADER_COMMENTPOST_URL],
        );
    }

    public function getHeaderSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_HEADER_COMMENTPOST:
                return [PoP_Module_Processor_PostViewComponentHeaders::class, PoP_Module_Processor_PostViewComponentHeaders::MODULE_VIEWCOMPONENT_HEADER_POST];

            case self::MODULE_VIEWCOMPONENT_HEADER_COMMENTPOST_URL:
                return [PoP_Module_Processor_PostViewComponentHeaders::class, PoP_Module_Processor_PostViewComponentHeaders::MODULE_VIEWCOMPONENT_HEADER_POST_URL];
        }

        return parent::getHeaderSubmodule($componentVariation);
    }

    public function headerShowUrl(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_HEADER_COMMENTPOST_URL:
                return true;
        }

        return parent::headerShowUrl($componentVariation, $props);
    }
}



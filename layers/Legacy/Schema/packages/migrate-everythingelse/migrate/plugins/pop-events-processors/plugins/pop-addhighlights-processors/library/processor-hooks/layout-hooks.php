<?php

class PoP_Events_AddHighlights_ProcessorHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'GD_EM_Module_Processor_CustomFullViewLayouts:footer-modules',
            $this->getFooterSubcomponents(...),
            0
        );
    }

    public function getFooterSubcomponents($layouts)
    {
        $layouts[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS];
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoP_Events_AddHighlights_ProcessorHooks();

<?php

class PoP_Events_AddHighlights_ProcessorHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'GD_EM_Module_Processor_CustomFullViewLayouts:footer-modules',
            array($this, 'getFooterSubmodules'),
            0
        );
    }

    public function getFooterSubmodules($layouts)
    {
        $layouts[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS];
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoP_Events_AddHighlights_ProcessorHooks();

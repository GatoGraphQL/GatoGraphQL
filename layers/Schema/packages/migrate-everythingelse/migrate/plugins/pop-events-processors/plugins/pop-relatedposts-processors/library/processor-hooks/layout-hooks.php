<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Events_RelatedPosts_ProcessorHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'GD_EM_Module_Processor_CustomFullViewLayouts:footer-modules',
            array($this, 'getFooterSubmodules'),
            5
        );
    }

    public function getFooterSubmodules($layouts)
    {
        $layouts[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_LAZYSUBCOMPONENT_REFERENCEDBY];
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoP_Events_RelatedPosts_ProcessorHooks();

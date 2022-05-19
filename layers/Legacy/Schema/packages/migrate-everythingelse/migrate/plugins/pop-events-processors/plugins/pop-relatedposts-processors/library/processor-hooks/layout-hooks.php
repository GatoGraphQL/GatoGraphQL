<?php

class PoP_Events_RelatedPosts_ProcessorHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'GD_EM_Module_Processor_CustomFullViewLayouts:footer-modules',
            $this->getFooterSubcomponents(...),
            5
        );
    }

    public function getFooterSubcomponents($layouts)
    {
        $layouts[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_REFERENCEDBY];
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoP_Events_RelatedPosts_ProcessorHooks();

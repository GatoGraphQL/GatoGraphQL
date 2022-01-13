<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Events_AddComments_ProcessorHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'GD_EM_Module_Processor_CustomFullViewLayouts:footer-modules',
            array($this, 'getFooterSubmodules')
        );
    }

    public function getFooterSubmodules($layouts)
    {
        $layouts[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS];
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoP_Events_AddComments_ProcessorHooks();

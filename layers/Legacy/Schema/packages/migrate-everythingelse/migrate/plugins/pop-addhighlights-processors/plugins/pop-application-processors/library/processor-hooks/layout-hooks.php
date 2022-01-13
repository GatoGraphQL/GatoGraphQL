<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_AddHighlights_LayoutHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_CustomControlGroups:userpostinteraction:layouts',
            array($this, 'addUserpostinteractionLayouts')
        );
    }

    public function addUserpostinteractionLayouts($layouts)
    {
        $layouts[] = [PoP_AddHighlights_Module_Processor_PostButtons::class, PoP_AddHighlights_Module_Processor_PostButtons::MODULE_BUTTON_HIGHLIGHT_CREATEBTN];
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoP_AddHighlights_LayoutHooks();

<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_AddRelatedPosts_LayoutHooks
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
        $layouts[] = [PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups::class, PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_ADDRELATEDPOST];
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoP_AddRelatedPosts_LayoutHooks();

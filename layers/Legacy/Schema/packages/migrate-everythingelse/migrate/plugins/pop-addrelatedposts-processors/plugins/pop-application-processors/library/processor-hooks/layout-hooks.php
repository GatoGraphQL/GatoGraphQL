<?php

class PoP_AddRelatedPosts_LayoutHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomControlGroups:userpostinteraction:layouts',
            $this->addUserpostinteractionLayouts(...)
        );
    }

    public function addUserpostinteractionLayouts($layouts)
    {
        $layouts[] = [PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups::class, PoP_AddRelatedPosts_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_ADDRELATEDPOST];
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoP_AddRelatedPosts_LayoutHooks();

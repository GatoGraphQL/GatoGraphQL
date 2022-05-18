<?php

use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class PoP_Application_UserStance_LayoutHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomContentBlocks:single-sidebar:top',
            $this->getTopSidebar(...),
            10,
            2
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomContentBlocks:single-sidebar:bottom',
            $this->getBottomSidebar(...),
            10,
            2
        );
    }

    public function getTopSidebar($sidebar, $post_id)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($post_id) == POP_USERSTANCE_POSTTYPE_USERSTANCE) {
            return [UserStance_Module_Processor_CustomPostLayoutSidebars::class, UserStance_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STANCE];
        }

        return $sidebar;
    }

    public function getBottomSidebar($sidebar, $post_id)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($post_id) == POP_USERSTANCE_POSTTYPE_USERSTANCE) {
            return [PoPCore_Module_Processor_Contents::class, PoPCore_Module_Processor_Contents::COMPONENT_CONTENT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL];
        }

        return $sidebar;
    }
}

/**
 * Initialization
 */
new PoP_Application_UserStance_LayoutHooks();

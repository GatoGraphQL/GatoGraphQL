<?php
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class PoP_LocationPosts_BlockHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomContentBlocks:single-sidebar:top',
            $this->getTopSidebar(...),
            10,
            2
        );
    }

    public function getTopSidebar($sidebar, $post_id)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($post_id) == POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST) {
            return [GD_Custom_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_Custom_EM_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LOCATIONPOST];
        }

        return $sidebar;
    }
}

/**
 * Initialization
 */
new PoP_LocationPosts_BlockHooks();

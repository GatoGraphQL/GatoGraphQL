<?php
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class PoP_ApplicationProcessors_AddHighlights_BlockHooks
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
        if ($customPostTypeAPI->getCustomPostType($post_id) == POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT) {
            return [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT];
        }

        return $sidebar;
    }

    public function getBottomSidebar($sidebar, $post_id)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($post_id) == POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT) {
            return [PoPCore_Module_Processor_Contents::class, PoPCore_Module_Processor_Contents::MODULE_CONTENT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL];
        }

        return $sidebar;
    }
}

/**
 * Initialization
 */
new PoP_ApplicationProcessors_AddHighlights_BlockHooks();

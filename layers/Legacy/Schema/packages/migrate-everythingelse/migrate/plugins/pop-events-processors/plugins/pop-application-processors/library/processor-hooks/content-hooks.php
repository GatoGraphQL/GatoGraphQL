<?php
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Events\Facades\EventTypeAPIFacade;

class PoPTheme_Wassup_EM_ContentHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_CustomContentBlocks:single-sidebar:top',
            array($this, 'getTopSidebar'),
            10,
            2
        );
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_CustomContentBlocks:single-sidebar:bottom',
            array($this, 'getBottomSidebar'),
            10,
            2
        );
    }

    public function getTopSidebar($sidebar, $post_id)
    {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($post_id) == $eventTypeAPI->getEventCustomPostType()) {
            return $eventTypeAPI->isFutureEvent($post_id) ?
                [GD_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_EM_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT] :
                [GD_EM_Module_Processor_CustomPostLayoutSidebars::class, GD_EM_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT];
        }

        return $sidebar;
    }

    public function getBottomSidebar($sidebar, $post_id)
    {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($post_id) == $eventTypeAPI->getEventCustomPostType()) {
            return [PoPCore_Module_Processor_Contents::class, PoPCore_Module_Processor_Contents::MODULE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL];
        }

        return $sidebar;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_EM_ContentHooks();

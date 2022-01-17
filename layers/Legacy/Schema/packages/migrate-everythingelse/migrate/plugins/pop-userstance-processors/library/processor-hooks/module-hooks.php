<?php
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class PoPTheme_UserStance_ModuleHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_MainGroups:modules:single',
            array($this, 'getSingleSubmodules')
        );
    }

    public function getSingleSubmodules($modules)
    {

        // Only for Links/Posts/Stories/Discussions/Announcements/Events
        $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        $add = in_array($customPostTypeAPI->getCustomPostType($post_id), $cmsapplicationpostsapi->getAllcontentPostTypes());
        $add = \PoP\Root\App::applyFilters(
            'PoPTheme_UserStance_ModuleHooks:single-block:add-createorupdate-vote',
            $add,
            $post_id
        );
        if ($add) {
            // Add the "What do you think about TPP" Create Block
            array_splice(
                $modules,
                array_search(
                    [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_SINGLE_CONTENT],
                    $modules
                )+1,
                0,
                array(
                    [UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_SINGLEPOSTSTANCE_CREATEORUPDATE]
                )
            );
        }
        return $modules;
    }
}

/**
 * Initialization
 */
new PoPTheme_UserStance_ModuleHooks();

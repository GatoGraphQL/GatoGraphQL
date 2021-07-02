<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

class PoPTheme_UserStance_ModuleHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_MainGroups:modules:single',
            array($this, 'getSingleSubmodules')
        );
    }

    public function getSingleSubmodules($modules)
    {

        // Only for Links/Posts/Stories/Discussions/Announcements/Events
        $vars = ApplicationState::getVars();
        $post_id = $vars['routing-state']['queried-object-id'];
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        $add = in_array($customPostTypeAPI->getCustomPostType($post_id), $cmsapplicationpostsapi->getAllcontentPostTypes());
        $add = HooksAPIFacade::getInstance()->applyFilters(
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

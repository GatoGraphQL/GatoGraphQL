<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

class GetPoPDemo_URE_GroupHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_CustomGroups:modules:author_widgetarea',
            array($this, 'getAuthortopWidgetSubmodules'),
            0
        );
    }

    public function getAuthortopWidgetSubmodules($modules)
    {

        // Add the members only for communities
        $vars = ApplicationState::getVars();
        $author = $vars['routing-state']['queried-object-id'];
        if (gdUreIsCommunity($author)) {
            if (defined('POP_APPLICATIONPROCESSORS_INITIALIZED')) {
                $modules[] = [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL];
            }
        }
        
        return $modules;
    }
}

/**
 * Initialization
 */
new GetPoPDemo_URE_GroupHooks();

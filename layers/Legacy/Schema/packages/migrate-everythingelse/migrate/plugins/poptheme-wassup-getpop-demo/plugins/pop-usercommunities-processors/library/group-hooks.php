<?php
use PoP\ComponentModel\State\ApplicationState;

class GetPoPDemo_URE_GroupHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomGroups:modules:author_widgetarea',
            $this->getAuthortopWidgetSubmodules(...),
            0
        );
    }

    public function getAuthortopWidgetSubmodules($componentVariations)
    {

        // Add the members only for communities
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        if (gdUreIsCommunity($author)) {
            if (defined('POP_APPLICATIONPROCESSORS_INITIALIZED')) {
                $componentVariations[] = [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL];
            }
        }
        
        return $componentVariations;
    }
}

/**
 * Initialization
 */
new GetPoPDemo_URE_GroupHooks();

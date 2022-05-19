<?php
use PoP\ComponentModel\State\ApplicationState;

class GetPoPDemo_URE_GroupHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomGroups:components:author_widgetarea',
            $this->getAuthortopWidgetSubcomponents(...),
            0
        );
    }

    public function getAuthortopWidgetSubcomponents($components)
    {

        // Add the members only for communities
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        if (gdUreIsCommunity($author)) {
            if (defined('POP_APPLICATIONPROCESSORS_INITIALIZED')) {
                $components[] = [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL];
            }
        }
        
        return $components;
    }
}

/**
 * Initialization
 */
new GetPoPDemo_URE_GroupHooks();

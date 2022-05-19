<?php
use PoP\ComponentModel\State\ApplicationState;

class UREPoP_RoleProcessors_ControlGroup_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomControlGroups:blockauthorpostlist:layouts',
            $this->getLayoutSubcomponents(...)
        );
    }

    public function getLayoutSubcomponents($layouts)
    {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);

        // Add the Switch Community/Community+Members if the author is a community
        if (gdUreIsCommunity($author)) {
            array_unshift(
                $layouts, 
                [GD_URE_Module_Processor_ControlButtonGroups::class, GD_URE_Module_Processor_ControlButtonGroups::COMPONENT_URE_CONTROLBUTTONGROUP_CONTENTSOURCE]
            );
        }

        return $layouts;
    }
}

/**
 * Initialization
 */
new UREPoP_RoleProcessors_ControlGroup_Hooks();

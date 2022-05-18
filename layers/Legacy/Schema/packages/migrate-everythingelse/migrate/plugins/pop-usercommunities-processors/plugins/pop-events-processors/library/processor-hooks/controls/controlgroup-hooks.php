<?php
use PoP\ComponentModel\State\ApplicationState;

class UREPoP_RoleProcessors_EM_ControlGroup_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'GD_EM_Module_Processor_CustomControlGroups:blockauthoreventlist:layouts',
            $this->getLayoutSubmodules(...)
        );

        // Also the Past Events link on the Author Events top controlgroup
        \PoP\Root\App::addFilter(
            'GD_EM_Module_Processor_CustomAnchorControls:pastevents:url',
            'gdUreAddSourceParam',
            10,
            2
        );
    }

    public function getLayoutSubmodules($layouts)
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
new UREPoP_RoleProcessors_EM_ControlGroup_Hooks();

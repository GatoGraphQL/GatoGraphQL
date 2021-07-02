<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

class UREPoP_RoleProcessors_EM_ControlGroup_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'GD_EM_Module_Processor_CustomControlGroups:blockauthoreventlist:layouts',
            array($this, 'getLayoutSubmodules')
        );

        // Also the Past Events link on the Author Events top controlgroup
        HooksAPIFacade::getInstance()->addFilter(
            'GD_EM_Module_Processor_CustomAnchorControls:pastevents:url',
            'gdUreAddSourceParam',
            10,
            2
        );
    }

    public function getLayoutSubmodules($layouts)
    {
        $vars = ApplicationState::getVars();
        $author = $vars['routing-state']['queried-object-id'];

        // Add the Switch Community/Community+Members if the author is a community
        if (gdUreIsCommunity($author)) {
            array_unshift(
                $layouts, 
                [GD_URE_Module_Processor_ControlButtonGroups::class, GD_URE_Module_Processor_ControlButtonGroups::MODULE_URE_CONTROLBUTTONGROUP_CONTENTSOURCE]
            );
        }

        return $layouts;
    }
}

/**
 * Initialization
 */
new UREPoP_RoleProcessors_EM_ControlGroup_Hooks();

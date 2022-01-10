<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

class UREPoP_RoleProcessors_ControlGroup_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_CustomControlGroups:blockauthorpostlist:layouts',
            array($this, 'getLayoutSubmodules')
        );
    }

    public function getLayoutSubmodules($layouts)
    {
        $vars = ApplicationState::getVars();
        $author = $vars['routing']['queried-object-id'];

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
new UREPoP_RoleProcessors_ControlGroup_Hooks();

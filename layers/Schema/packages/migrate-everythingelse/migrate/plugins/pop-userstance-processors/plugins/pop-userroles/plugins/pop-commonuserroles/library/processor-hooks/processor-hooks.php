<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

class UserStance_URE_ProcessorHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoPVP_Module_Processor_SidebarMultiples:inner-modules:authorstances',
            array($this, 'getInnerSubmodules')
        );
    }

    public function getInnerSubmodules($modules)
    {
        $vars = ApplicationState::getVars();
        $author = $vars['routing-state']['queried-object-id'];
        if (gdUreIsOrganization($author)) {
            $modules[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION];
        } elseif (gdUreIsIndividual($author)) {
            $modules[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL];
        }
        return $modules;
    }
}

/**
 * Initialization
 */
new UserStance_URE_ProcessorHooks();

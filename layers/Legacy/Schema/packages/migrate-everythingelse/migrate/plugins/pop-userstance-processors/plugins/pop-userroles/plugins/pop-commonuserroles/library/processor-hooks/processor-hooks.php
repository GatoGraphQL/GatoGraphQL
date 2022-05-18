<?php
use PoP\ComponentModel\State\ApplicationState;

class UserStance_URE_ProcessorHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoPVP_Module_Processor_SidebarMultiples:inner-modules:authorstances',
            $this->getInnerSubmodules(...)
        );
    }

    public function getInnerSubmodules($componentVariations)
    {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        if (gdUreIsOrganization($author)) {
            $componentVariations[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION];
        } elseif (gdUreIsIndividual($author)) {
            $componentVariations[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL];
        }
        return $componentVariations;
    }
}

/**
 * Initialization
 */
new UserStance_URE_ProcessorHooks();

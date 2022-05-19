<?php
use PoP\ComponentModel\State\ApplicationState;

class UserStance_URE_ProcessorHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoPVP_Module_Processor_SidebarMultiples:inner-modules:authorstances',
            $this->getInnerSubcomponents(...)
        );
    }

    public function getInnerSubcomponents($components)
    {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        if (gdUreIsOrganization($author)) {
            $components[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION];
        } elseif (gdUreIsIndividual($author)) {
            $components[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL];
        }
        return $components;
    }
}

/**
 * Initialization
 */
new UserStance_URE_ProcessorHooks();

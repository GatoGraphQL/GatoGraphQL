<?php
use PoP\ComponentModel\State\ApplicationState;

class PoP_LocationPosts_CommonUserRoles_ProcessorHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoPSP_URE_EM_Module_Processor_SidebarMultiples:inner-modules:author',
            $this->getInnerSubmodules(...)
        );
    }

    public function getInnerSubmodules($components)
    {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        if (gdUreIsOrganization($author)) {
            $components[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION];
        } elseif (gdUreIsIndividual($author)) {
            $components[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL];
        }
        return $components;
    }
}

/**
 * Initialization
 */
new PoP_LocationPosts_CommonUserRoles_ProcessorHooks();

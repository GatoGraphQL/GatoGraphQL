<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_LocationPosts_CommonUserRoles_ProcessorHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoPSP_URE_EM_Module_Processor_SidebarMultiples:inner-modules:author',
            array($this, 'getInnerSubmodules')
        );
    }

    public function getInnerSubmodules($modules)
    {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
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
new PoP_LocationPosts_CommonUserRoles_ProcessorHooks();

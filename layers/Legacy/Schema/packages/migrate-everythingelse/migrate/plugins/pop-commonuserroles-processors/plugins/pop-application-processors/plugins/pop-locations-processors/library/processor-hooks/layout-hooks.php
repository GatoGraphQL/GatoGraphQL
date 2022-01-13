<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('PoP_EM_Module_Processor_SidebarMultiples:inner-modules', 'wassupUreAddAuthorSidebar', 10, 2);
function wassupUreAddAuthorSidebar($modules, $author)
{
    if (gdUreIsOrganization($author)) {
        $modules[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION];
    } elseif (gdUreIsIndividual($author)) {
        $modules[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL];
    }
    return $modules;
}

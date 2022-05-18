<?php

\PoP\Root\App::addFilter('PoP_EM_Module_Processor_SidebarMultiples:inner-modules', 'wassupUreAddAuthorSidebar', 10, 2);
function wassupUreAddAuthorSidebar($components, $author)
{
    if (gdUreIsOrganization($author)) {
        $components[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION];
    } elseif (gdUreIsIndividual($author)) {
        $components[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL];
    }
    return $components;
}

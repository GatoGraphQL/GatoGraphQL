<?php

\PoP\Root\App::addFilter('PoP_UserCommunities_Module_Processor_SidebarMultiples:sidebar-layouts', 'gdUreAddSidebarLayouts', 10, 3);
function gdUreAddSidebarLayouts($layouts, $author, array $component)
{
    if (gdUreIsOrganization($author)) {
        $layouts[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION];
    } elseif (gdUreIsIndividual($author)) {
        $layouts[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL];
    }

    return $layouts;
}

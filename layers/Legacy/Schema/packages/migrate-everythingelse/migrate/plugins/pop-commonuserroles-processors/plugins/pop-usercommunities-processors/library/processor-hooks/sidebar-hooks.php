<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('PoP_UserCommunities_Module_Processor_SidebarMultiples:sidebar-layouts', 'gdUreAddSidebarLayouts', 10, 3);
function gdUreAddSidebarLayouts($layouts, $author, array $module)
{
    if (gdUreIsOrganization($author)) {
        $layouts[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_AUTHOR_SIDEBAR_ORGANIZATION];
    } elseif (gdUreIsIndividual($author)) {
        $layouts[] = [GD_URE_Module_Processor_CustomSidebarDataloads::class, GD_URE_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_AUTHOR_SIDEBAR_INDIVIDUAL];
    }

    return $layouts;
}

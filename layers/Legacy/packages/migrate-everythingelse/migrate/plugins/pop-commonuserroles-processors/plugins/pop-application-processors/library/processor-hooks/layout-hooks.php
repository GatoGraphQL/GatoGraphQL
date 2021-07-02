<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

/**
 * Layout templates
 */
HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_CustomContentBlocks:author:sidebar', 'gdUreAuthorsidebarLayout');
function gdUreAuthorsidebarLayout($layout)
{
    $vars = ApplicationState::getVars();
    $author = $vars['routing-state']['queried-object-id'];
    if (gdUreIsOrganization($author)) {
        return [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_ORGANIZATION];
    } elseif (gdUreIsIndividual($author)) {
        return [GD_URE_Module_Processor_CustomUserLayoutSidebars::class, GD_URE_Module_Processor_CustomUserLayoutSidebars::MODULE_LAYOUT_USERSIDEBAR_COMPACTHORIZONTAL_INDIVIDUAL];
    }

    return $layout;
}

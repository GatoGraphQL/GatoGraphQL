<?php

/**
 * Map format
 */
\PoP\Root\App::addFilter('PoP_Module_Processor_SectionTabPanelComponents:modules', 'gdEmSectiontabpanelSubmodules', 10, 2);
function gdEmSectiontabpanelSubmodules($subComponents, array $component)
{
    if ($component == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_USERS]) {
        $subComponents[] = [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_USERS_SCROLLMAP];
    } elseif ($component == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_SEARCHUSERS]) {
        $subComponents[] = [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP];
    }

    return $subComponents;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_SectionTabPanelComponents:panel_headers', 'gdEmSectiontabpanelPanelheaders', 10, 2);
function gdEmSectiontabpanelPanelheaders($panelheaders, array $component)
{
    if ($component == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_USERS]) {
        $panelheaders[] = [
            'header-submodule' => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_USERS_SCROLLMAP],
        ];
    } elseif ($component == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_SEARCHUSERS]) {
        $panelheaders[] = [
            'header-submodule' => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP],
        ];
    }

    return $panelheaders;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_SingleSectionTabPanelComponents:modules', 'gdEmSinglesectiontabpanelSubmodules', 10, 2);
function gdEmSinglesectiontabpanelSubmodules($subComponents, array $component)
{
    if ($component == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLEAUTHORS]) {
        $subComponents[] = [PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP];
    }

    return $subComponents;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_SingleSectionTabPanelComponents:panel_headers', 'gdEmSinglesectiontabpanelPanelheaders', 10, 2);
function gdEmSinglesectiontabpanelPanelheaders($panelheaders, array $component)
{
    if ($component == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLEAUTHORS]) {
        $panelheaders[] = [
            'header-submodule' => [PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP],
        ];
    }

    return $panelheaders;
}

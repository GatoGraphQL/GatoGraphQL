<?php

/**
 * Map format
 */
\PoP\Root\App::addFilter('PoP_Module_Processor_SectionTabPanelComponents:modules', 'gdEmSectiontabpanelSubmodules', 10, 2);
function gdEmSectiontabpanelSubmodules($subComponentVariations, array $componentVariation)
{
    if ($componentVariation == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_USERS]) {
        $subComponentVariations[] = [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_USERS_SCROLLMAP];
    } elseif ($componentVariation == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_SEARCHUSERS]) {
        $subComponentVariations[] = [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP];
    }

    return $subComponentVariations;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_SectionTabPanelComponents:panel_headers', 'gdEmSectiontabpanelPanelheaders', 10, 2);
function gdEmSectiontabpanelPanelheaders($panelheaders, array $componentVariation)
{
    if ($componentVariation == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_USERS]) {
        $panelheaders[] = [
            'header-submodule' => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_USERS_SCROLLMAP],
        ];
    } elseif ($componentVariation == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_SEARCHUSERS]) {
        $panelheaders[] = [
            'header-submodule' => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP],
        ];
    }

    return $panelheaders;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_SingleSectionTabPanelComponents:modules', 'gdEmSinglesectiontabpanelSubmodules', 10, 2);
function gdEmSinglesectiontabpanelSubmodules($subComponentVariations, array $componentVariation)
{
    if ($componentVariation == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLEAUTHORS]) {
        $subComponentVariations[] = [PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP];
    }

    return $subComponentVariations;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_SingleSectionTabPanelComponents:panel_headers', 'gdEmSinglesectiontabpanelPanelheaders', 10, 2);
function gdEmSinglesectiontabpanelPanelheaders($panelheaders, array $componentVariation)
{
    if ($componentVariation == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLEAUTHORS]) {
        $panelheaders[] = [
            'header-submodule' => [PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP],
        ];
    }

    return $panelheaders;
}

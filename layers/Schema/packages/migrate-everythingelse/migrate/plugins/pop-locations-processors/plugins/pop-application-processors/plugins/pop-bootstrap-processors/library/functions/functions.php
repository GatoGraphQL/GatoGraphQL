<?php
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Map format
 */
HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_SectionTabPanelComponents:modules', 'gdEmSectiontabpanelSubmodules', 10, 2);
function gdEmSectiontabpanelSubmodules($submodules, array $module)
{
    if ($module == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_USERS]) {
        $submodules[] = [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_USERS_SCROLLMAP];
    } elseif ($module == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_SEARCHUSERS]) {
        $submodules[] = [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP];
    }

    return $submodules;
}
HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_SectionTabPanelComponents:panel_headers', 'gdEmSectiontabpanelPanelheaders', 10, 2);
function gdEmSectiontabpanelPanelheaders($panelheaders, array $module)
{
    if ($module == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_USERS]) {
        $panelheaders[] = [
            'header-submodule' => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_USERS_SCROLLMAP],
        ];
    } elseif ($module == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::MODULE_TABPANEL_SEARCHUSERS]) {
        $panelheaders[] = [
            'header-submodule' => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP],
        ];
    }

    return $panelheaders;
}
HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_SingleSectionTabPanelComponents:modules', 'gdEmSinglesectiontabpanelSubmodules', 10, 2);
function gdEmSinglesectiontabpanelSubmodules($submodules, array $module)
{
    if ($module == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLEAUTHORS]) {
        $submodules[] = [PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP];
    }

    return $submodules;
}
HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_SingleSectionTabPanelComponents:panel_headers', 'gdEmSinglesectiontabpanelPanelheaders', 10, 2);
function gdEmSinglesectiontabpanelPanelheaders($panelheaders, array $module)
{
    if ($module == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLEAUTHORS]) {
        $panelheaders[] = [
            'header-submodule' => [PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLLMAP],
        ];
    }

    return $panelheaders;
}

<?php

/**
 * Map format
 */
\PoP\Root\App::addFilter('PoP_Module_Processor_SectionTabPanelComponents:modules', 'gdEmSectiontabpanelSubcomponents', 10, 2);
function gdEmSectiontabpanelSubcomponents($subComponents, \PoP\ComponentModel\Component\Component $component)
{
    if ($component == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_USERS]) {
        $subComponents[] = [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_USERS_SCROLLMAP];
    } elseif ($component == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_SEARCHUSERS]) {
        $subComponents[] = [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_SCROLLMAP];
    }

    return $subComponents;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_SectionTabPanelComponents:panel_headers', 'gdEmSectiontabpanelPanelheaders', 10, 2);
function gdEmSectiontabpanelPanelheaders($panelheaders, \PoP\ComponentModel\Component\Component $component)
{
    if ($component == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_USERS]) {
        $panelheaders[] = [
            'header-subcomponent' => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_USERS_SCROLLMAP],
        ];
    } elseif ($component == [PoP_Module_Processor_SectionTabPanelComponents::class, PoP_Module_Processor_SectionTabPanelComponents::COMPONENT_TABPANEL_SEARCHUSERS]) {
        $panelheaders[] = [
            'header-subcomponent' => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_SCROLLMAP],
        ];
    }

    return $panelheaders;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_SingleSectionTabPanelComponents:modules', 'gdEmSinglesectiontabpanelSubcomponents', 10, 2);
function gdEmSinglesectiontabpanelSubcomponents($subComponents, \PoP\ComponentModel\Component\Component $component)
{
    if ($component == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLEAUTHORS]) {
        $subComponents[] = [PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLLMAP];
    }

    return $subComponents;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_SingleSectionTabPanelComponents:panel_headers', 'gdEmSinglesectiontabpanelPanelheaders', 10, 2);
function gdEmSinglesectiontabpanelPanelheaders($panelheaders, \PoP\ComponentModel\Component\Component $component)
{
    if ($component == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLEAUTHORS]) {
        $panelheaders[] = [
            'header-subcomponent' => [PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLLMAP],
        ];
    }

    return $panelheaders;
}

<?php

/**
 * Map format
 */
\PoP\Root\App::addFilter('PoP_Module_Processor_AuthorSectionTabPanelComponents:modules', 'gdEmSocialnetworkAuthorsectiontabpanelSubcomponents', 10, 2);
function gdEmSocialnetworkAuthorsectiontabpanelSubcomponents($subcomponents, \PoP\ComponentModel\Component\Component $component)
{
    if ($component == [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORFOLLOWERS]) {
        $subcomponents[] = [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP];
    } elseif ($component == [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORFOLLOWINGUSERS]) {
        $subcomponents[] = [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP];
    }

    return $subcomponents;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_AuthorSectionTabPanelComponents:panel_headers', 'gdEmSocialnetworkAuthorsectiontabpanelPanelheaders', 10, 2);
function gdEmSocialnetworkAuthorsectiontabpanelPanelheaders($panelheaders, \PoP\ComponentModel\Component\Component $component)
{
    if ($component == [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORFOLLOWERS]) {
        $panelheaders[] = [
            'header-subcomponent' => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP],
        ];
    } elseif ($component == [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::COMPONENT_TABPANEL_AUTHORFOLLOWINGUSERS]) {
        $panelheaders[] = [
            'header-subcomponent' => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP],
        ];
    }

    return $panelheaders;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_SingleSectionTabPanelComponents:modules', 'gdEmSocialnetworkSinglesectiontabpanelSubcomponents', 10, 2);
function gdEmSocialnetworkSinglesectiontabpanelSubcomponents($subcomponents, \PoP\ComponentModel\Component\Component $component)
{
    if ($component == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLERECOMMENDEDBY]) {
        $subcomponents[] = [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP];
    } elseif ($component == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLEUPVOTEDBY]) {
        $subcomponents[] = [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP];
    } elseif ($component == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLEDOWNVOTEDBY]) {
        $subcomponents[] = [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP];
    }

    return $subcomponents;
}

\PoP\Root\App::addFilter('PoP_Module_Processor_SingleSectionTabPanelComponents:panel_headers', 'gdEmSocialnetworkSinglesectiontabpanelPanelheaders', 10, 2);
function gdEmSocialnetworkSinglesectiontabpanelPanelheaders($panelheaders, \PoP\ComponentModel\Component\Component $component)
{
    if ($component == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLERECOMMENDEDBY]) {
        $panelheaders[] = [
            'header-subcomponent' => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP],
        ];
    } elseif ($component == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLEUPVOTEDBY]) {
        $panelheaders[] = [
            'header-subcomponent' => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP],
        ];
    } elseif ($component == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::COMPONENT_TABPANEL_SINGLEDOWNVOTEDBY]) {
        $panelheaders[] = [
            'header-subcomponent' => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP],
        ];
    }

    return $panelheaders;
}

\PoP\Root\App::addFilter('PoP_Module_Processor_TagSectionTabPanelComponents:modules', 'gdEmSocialnetworkTagsectiontabpanelSubcomponents', 10, 2);
function gdEmSocialnetworkTagsectiontabpanelSubcomponents($subcomponents, \PoP\ComponentModel\Component\Component $component)
{
    if ($component == [PoP_Module_Processor_TagSectionTabPanelComponents::class, PoP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGSUBSCRIBERS]) {
        $subcomponents[] = [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP];
    }

    return $subcomponents;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_TagSectionTabPanelComponents:panel_headers', 'gdEmSocialnetworkTagsectiontabpanelPanelheaders', 10, 2);
function gdEmSocialnetworkTagsectiontabpanelPanelheaders($panelheaders, \PoP\ComponentModel\Component\Component $component)
{
    if ($component == [PoP_Module_Processor_TagSectionTabPanelComponents::class, PoP_Module_Processor_TagSectionTabPanelComponents::COMPONENT_TABPANEL_TAGSUBSCRIBERS]) {
        $panelheaders[] = [
            'header-subcomponent' => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP],
        ];
    }

    return $panelheaders;
}

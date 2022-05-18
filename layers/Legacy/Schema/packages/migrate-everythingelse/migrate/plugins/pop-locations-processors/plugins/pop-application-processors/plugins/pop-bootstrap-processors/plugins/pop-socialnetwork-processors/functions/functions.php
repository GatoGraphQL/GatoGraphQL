<?php

/**
 * Map format
 */
\PoP\Root\App::addFilter('PoP_Module_Processor_AuthorSectionTabPanelComponents:modules', 'gdEmSocialnetworkAuthorsectiontabpanelSubmodules', 10, 2);
function gdEmSocialnetworkAuthorsectiontabpanelSubmodules($subComponentVariations, array $componentVariation)
{
    if ($componentVariation == [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORFOLLOWERS]) {
        $subComponentVariations[] = [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP];
    } elseif ($componentVariation == [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORFOLLOWINGUSERS]) {
        $subComponentVariations[] = [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP];
    }

    return $subComponentVariations;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_AuthorSectionTabPanelComponents:panel_headers', 'gdEmSocialnetworkAuthorsectiontabpanelPanelheaders', 10, 2);
function gdEmSocialnetworkAuthorsectiontabpanelPanelheaders($panelheaders, array $componentVariation)
{
    if ($componentVariation == [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORFOLLOWERS]) {
        $panelheaders[] = [
            'header-submodule' => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLLMAP],
        ];
    } elseif ($componentVariation == [PoP_Module_Processor_AuthorSectionTabPanelComponents::class, PoP_Module_Processor_AuthorSectionTabPanelComponents::MODULE_TABPANEL_AUTHORFOLLOWINGUSERS]) {
        $panelheaders[] = [
            'header-submodule' => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLLMAP],
        ];
    }

    return $panelheaders;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_SingleSectionTabPanelComponents:modules', 'gdEmSocialnetworkSinglesectiontabpanelSubmodules', 10, 2);
function gdEmSocialnetworkSinglesectiontabpanelSubmodules($subComponentVariations, array $componentVariation)
{
    if ($componentVariation == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLERECOMMENDEDBY]) {
        $subComponentVariations[] = [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP];
    } elseif ($componentVariation == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLEUPVOTEDBY]) {
        $subComponentVariations[] = [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP];
    } elseif ($componentVariation == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLEDOWNVOTEDBY]) {
        $subComponentVariations[] = [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP];
    }

    return $subComponentVariations;
}

\PoP\Root\App::addFilter('PoP_Module_Processor_SingleSectionTabPanelComponents:panel_headers', 'gdEmSocialnetworkSinglesectiontabpanelPanelheaders', 10, 2);
function gdEmSocialnetworkSinglesectiontabpanelPanelheaders($panelheaders, array $componentVariation)
{
    if ($componentVariation == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLERECOMMENDEDBY]) {
        $panelheaders[] = [
            'header-submodule' => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLLMAP],
        ];
    } elseif ($componentVariation == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLEUPVOTEDBY]) {
        $panelheaders[] = [
            'header-submodule' => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLLMAP],
        ];
    } elseif ($componentVariation == [PoP_Module_Processor_SingleSectionTabPanelComponents::class, PoP_Module_Processor_SingleSectionTabPanelComponents::MODULE_TABPANEL_SINGLEDOWNVOTEDBY]) {
        $panelheaders[] = [
            'header-submodule' => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLLMAP],
        ];
    }

    return $panelheaders;
}

\PoP\Root\App::addFilter('PoP_Module_Processor_TagSectionTabPanelComponents:modules', 'gdEmSocialnetworkTagsectiontabpanelSubmodules', 10, 2);
function gdEmSocialnetworkTagsectiontabpanelSubmodules($subComponentVariations, array $componentVariation)
{
    if ($componentVariation == [PoP_Module_Processor_TagSectionTabPanelComponents::class, PoP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGSUBSCRIBERS]) {
        $subComponentVariations[] = [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP];
    }

    return $subComponentVariations;
}
\PoP\Root\App::addFilter('PoP_Module_Processor_TagSectionTabPanelComponents:panel_headers', 'gdEmSocialnetworkTagsectiontabpanelPanelheaders', 10, 2);
function gdEmSocialnetworkTagsectiontabpanelPanelheaders($panelheaders, array $componentVariation)
{
    if ($componentVariation == [PoP_Module_Processor_TagSectionTabPanelComponents::class, PoP_Module_Processor_TagSectionTabPanelComponents::MODULE_TABPANEL_TAGSUBSCRIBERS]) {
        $panelheaders[] = [
            'header-submodule' => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_TAGSUBSCRIBERS_SCROLLMAP],
        ];
    }

    return $panelheaders;
}

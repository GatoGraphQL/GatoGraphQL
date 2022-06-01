<?php

class PoP_UserCommunities_ComponentProcessor_AuthorSectionTabPanelComponents extends PoP_Module_Processor_AuthorSectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_AUTHORCOMMUNITYMEMBERS = 'tabpanel-authorcommunitymembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_AUTHORCOMMUNITYMEMBERS],
        );
    }

    protected function getDefaultActivepanelFormat(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORCOMMUNITYMEMBERS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORUSERS);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORCOMMUNITYMEMBERS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
                        [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORCOMMUNITYMEMBERS:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
                        'subheader-subcomponents' =>  array(
                            [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
                            [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
                            [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
                        ),
                    ],
                    [
                        'header-subcomponent' => [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
                    ],
                );
                break;
        }

        if ($ret) {
            return \PoP\Root\App::applyFilters('GD_URE_Module_Processor_AuthorSectionTabPanels:panel_headers', $ret, $component);
        }

        return parent::getPanelHeaders($component, $props);
    }
}


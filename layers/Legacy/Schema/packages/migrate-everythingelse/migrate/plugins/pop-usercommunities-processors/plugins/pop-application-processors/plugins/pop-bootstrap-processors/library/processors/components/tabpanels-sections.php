<?php

class PoP_UserCommunities_ComponentProcessor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_COMMUNITIES = 'tabpanel-communities';
    public final const COMPONENT_TABPANEL_MYMEMBERS = 'tabpanel-mymembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_COMMUNITIES],
            [self::class, self::COMPONENT_TABPANEL_MYMEMBERS],
        );
    }

    protected function getDefaultActivepanelFormat(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_TABPANEL_COMMUNITIES:
            case self::COMPONENT_TABPANEL_MYMEMBERS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_TABPANEL_COMMUNITIES:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW],
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_DETAILS],
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL],
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_LIST],
                        [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLLMAP],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_MYMEMBERS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_UserCommunities_Module_Processor_MySectionDataloads::class, PoP_UserCommunities_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYMEMBERS_TABLE_EDIT],
                        [PoP_UserCommunities_Module_Processor_MySectionDataloads::class, PoP_UserCommunities_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_TABPANEL_COMMUNITIES:
                return array(
                    [
                        'header-subcomponent' => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_DETAILS],
                        'subheader-subcomponents' =>  array(
                            [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_DETAILS],
                            [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL],
                            [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLL_LIST],
                        ),
                    ],
                    [
                        'header-subcomponent' => [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_SCROLLMAP],
                    ],
                );
        }

        return parent::getPanelHeaders($component, $props);
    }
}

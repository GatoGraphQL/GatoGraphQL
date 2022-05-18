<?php

class PoP_UserCommunities_ComponentProcessor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_COMMUNITIES = 'tabpanel-communities';
    public final const MODULE_TABPANEL_MYMEMBERS = 'tabpanel-mymembers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_COMMUNITIES],
            [self::class, self::MODULE_TABPANEL_MYMEMBERS],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_COMMUNITIES:
            case self::MODULE_TABPANEL_MYMEMBERS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_COMMUNITIES:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW],
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS],
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL],
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLL_LIST],
                        [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYMEMBERS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_UserCommunities_Module_Processor_MySectionDataloads::class, PoP_UserCommunities_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYMEMBERS_TABLE_EDIT],
                        [PoP_UserCommunities_Module_Processor_MySectionDataloads::class, PoP_UserCommunities_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_COMMUNITIES:
                return array(
                    [
                        'header-submodule' => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS],
                        'subheader-submodules' =>  array(
                            [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLL_DETAILS],
                            [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLL_THUMBNAIL],
                            [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLL_LIST],
                        ),
                    ],
                    [
                        'header-submodule' => [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_COMMUNITIES_SCROLLMAP],
                    ],
                );
        }

        return parent::getPanelHeaders($module, $props);
    }
}

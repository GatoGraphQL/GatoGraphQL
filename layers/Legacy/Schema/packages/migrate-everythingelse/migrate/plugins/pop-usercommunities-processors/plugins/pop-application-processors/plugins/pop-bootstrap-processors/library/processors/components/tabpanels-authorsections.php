<?php

class PoP_UserCommunities_ModuleProcessor_AuthorSectionTabPanelComponents extends PoP_Module_Processor_AuthorSectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_AUTHORCOMMUNITYMEMBERS = 'tabpanel-authorcommunitymembers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_AUTHORCOMMUNITYMEMBERS],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_AUTHORCOMMUNITYMEMBERS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORUSERS);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_AUTHORCOMMUNITYMEMBERS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
                        [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
                        [PoP_UserCommunities_ModuleProcessor_CustomScrollMapSectionDataloads::class, PoP_UserCommunities_ModuleProcessor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_AUTHORCOMMUNITYMEMBERS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
                        'subheader-submodules' =>  array(
                            [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
                            [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
                            [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
                        ),
                    ],
                    [
                        'header-submodule' => [PoP_UserCommunities_ModuleProcessor_CustomScrollMapSectionDataloads::class, PoP_UserCommunities_ModuleProcessor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
                    ],
                );
                break;
        }

        if ($ret) {
            return \PoP\Root\App::applyFilters('GD_URE_Module_Processor_AuthorSectionTabPanels:panel_headers', $ret, $module);
        }

        return parent::getPanelHeaders($module, $props);
    }
}


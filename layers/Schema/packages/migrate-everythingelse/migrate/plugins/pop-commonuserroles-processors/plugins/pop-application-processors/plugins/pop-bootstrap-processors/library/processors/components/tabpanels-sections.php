<?php

class GD_URE_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public const MODULE_TABPANEL_ORGANIZATIONS = 'tabpanel-organizations';
    public const MODULE_TABPANEL_INDIVIDUALS = 'tabpanel-individuals';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_ORGANIZATIONS],
            [self::class, self::MODULE_TABPANEL_INDIVIDUALS],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_ORGANIZATIONS:
            case self::MODULE_TABPANEL_INDIVIDUALS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_ORGANIZATIONS:
                $ret = array_merge(
                    $ret,
                    array(
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW],
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS],
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL],
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_LIST],
                        [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLLMAP],
                    )
                );
                break;

            case self::MODULE_TABPANEL_INDIVIDUALS:
                $ret = array_merge(
                    $ret,
                    array(
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW],
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_DETAILS],
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL],
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_LIST],
                        [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLLMAP],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_ORGANIZATIONS:
                return array(
                    [
                        'header-submodule' => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS],
                        'subheader-submodules' =>  array(
                            [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS],
                            [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL],
                            [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLL_LIST],
                        ),
                    ],
                    [
                        'header-submodule' => [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_ORGANIZATIONS_SCROLLMAP],
                    ],
                );

            case self::MODULE_TABPANEL_INDIVIDUALS:
                return array(
                    [
                        'header-submodule' => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_DETAILS],
                        'subheader-submodules' =>  array(
                            [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_DETAILS],
                            [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL],
                            [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLL_LIST],
                        ),
                    ],
                    [
                        'header-submodule' => [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_INDIVIDUALS_SCROLLMAP],
                    ],
                );
        }

        return parent::getPanelHeaders($module, $props);
    }
}



<?php

class UserStance_Module_Processor_SingleSectionTabPanelComponents extends PoP_Module_Processor_SingleSectionTabPanelComponentsBase
{
    public const MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT = 'tabpanel-singlerelatedstancecontent';
    public const MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO = 'tabpanel-singlerelatedstancecontent-pro';
    public const MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST = 'tabpanel-singlerelatedstancecontent-against';
    public const MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL = 'tabpanel-singlerelatedstancecontent-neutral';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT],
            [self::class, self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO],
            [self::class, self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST],
            [self::class, self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT:
            case self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO:
            case self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST:
            case self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_SINGLESTANCES);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($module, $props);
    }
}



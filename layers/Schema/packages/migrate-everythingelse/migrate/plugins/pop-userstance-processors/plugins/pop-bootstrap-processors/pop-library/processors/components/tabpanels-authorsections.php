<?php

class UserStance_Module_Processor_AuthorSectionTabPanelComponents extends PoP_Module_Processor_AuthorSectionTabPanelComponentsBase
{
    public const MODULE_TABPANEL_AUTHORSTANCES = 'tabpanel-authorstances';
    public const MODULE_TABPANEL_AUTHORSTANCES_PRO = 'tabpanel-authorstances-pro';
    public const MODULE_TABPANEL_AUTHORSTANCES_NEUTRAL = 'tabpanel-authorstances-neutral';
    public const MODULE_TABPANEL_AUTHORSTANCES_AGAINST = 'tabpanel-authorstances-against';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_AUTHORSTANCES],
            [self::class, self::MODULE_TABPANEL_AUTHORSTANCES_PRO],
            [self::class, self::MODULE_TABPANEL_AUTHORSTANCES_NEUTRAL],
            [self::class, self::MODULE_TABPANEL_AUTHORSTANCES_AGAINST],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_AUTHORSTANCES:
            case self::MODULE_TABPANEL_AUTHORSTANCES_PRO:
            case self::MODULE_TABPANEL_AUTHORSTANCES_NEUTRAL:
            case self::MODULE_TABPANEL_AUTHORSTANCES_AGAINST:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_AUTHORSTANCES);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_AUTHORSTANCES:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_AUTHORSTANCES_PRO:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_AUTHORSTANCES_NEUTRAL:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_AUTHORSTANCES_AGAINST:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_AUTHORSTANCES:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_AUTHORSTANCES_PRO:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_AUTHORSTANCES_NEUTRAL:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_AUTHORSTANCES_AGAINST:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($module, $props);
    }
}



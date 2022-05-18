<?php

class UserStance_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_STANCES = 'tabpanel-stances';
    public final const MODULE_TABPANEL_STANCES_PRO = 'tabpanel-stances-pro';
    public final const MODULE_TABPANEL_STANCES_AGAINST = 'tabpanel-stances-against';
    public final const MODULE_TABPANEL_STANCES_NEUTRAL = 'tabpanel-stances-neutral';
    public final const MODULE_TABPANEL_STANCES_PRO_GENERAL = 'tabpanel-stances-pro-general';
    public final const MODULE_TABPANEL_STANCES_AGAINST_GENERAL = 'tabpanel-stances-against-general';
    public final const MODULE_TABPANEL_STANCES_NEUTRAL_GENERAL = 'tabpanel-stances-neutral-general';
    public final const MODULE_TABPANEL_STANCES_PRO_ARTICLE = 'tabpanel-stances-pro-article';
    public final const MODULE_TABPANEL_STANCES_AGAINST_ARTICLE = 'tabpanel-stances-against-article';
    public final const MODULE_TABPANEL_STANCES_NEUTRAL_ARTICLE = 'tabpanel-stances-neutral-article';
    public final const MODULE_TABPANEL_MYSTANCES = 'tabpanel-mystances';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_STANCES],
            [self::class, self::MODULE_TABPANEL_STANCES_PRO],
            [self::class, self::MODULE_TABPANEL_STANCES_AGAINST],
            [self::class, self::MODULE_TABPANEL_STANCES_NEUTRAL],
            [self::class, self::MODULE_TABPANEL_STANCES_PRO_GENERAL],
            [self::class, self::MODULE_TABPANEL_STANCES_AGAINST_GENERAL],
            [self::class, self::MODULE_TABPANEL_STANCES_NEUTRAL_GENERAL],
            [self::class, self::MODULE_TABPANEL_STANCES_PRO_ARTICLE],
            [self::class, self::MODULE_TABPANEL_STANCES_AGAINST_ARTICLE],
            [self::class, self::MODULE_TABPANEL_STANCES_NEUTRAL_ARTICLE],
            [self::class, self::MODULE_TABPANEL_MYSTANCES],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_STANCES:
            case self::MODULE_TABPANEL_STANCES_PRO:
            case self::MODULE_TABPANEL_STANCES_AGAINST:
            case self::MODULE_TABPANEL_STANCES_NEUTRAL:
            case self::MODULE_TABPANEL_STANCES_PRO_GENERAL:
            case self::MODULE_TABPANEL_STANCES_AGAINST_GENERAL:
            case self::MODULE_TABPANEL_STANCES_NEUTRAL_GENERAL:
            case self::MODULE_TABPANEL_STANCES_PRO_ARTICLE:
            case self::MODULE_TABPANEL_STANCES_AGAINST_ARTICLE:
            case self::MODULE_TABPANEL_STANCES_NEUTRAL_ARTICLE:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_STANCES);
            
            case self::MODULE_TABPANEL_MYSTANCES:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_MYSTANCES);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_STANCES:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_STANCES_PRO:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_STANCES_AGAINST:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_STANCES_NEUTRAL:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_STANCES_PRO_GENERAL:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_STANCES_AGAINST_GENERAL:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_STANCES_NEUTRAL_GENERAL:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_STANCES_PRO_ARTICLE:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_STANCES_AGAINST_ARTICLE:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_STANCES_NEUTRAL_ARTICLE:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYSTANCES:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_MySectionDataloads::class, UserStance_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYSTANCES_TABLE_EDIT],
                        [UserStance_Module_Processor_MySectionDataloads::class, UserStance_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYSTANCES_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_STANCES:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_STANCES_PRO:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_STANCES_AGAINST:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_STANCES_NEUTRAL:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_STANCES_PRO_GENERAL:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_GENERAL_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_STANCES_AGAINST_GENERAL:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_GENERAL_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_STANCES_NEUTRAL_GENERAL:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_GENERAL_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_STANCES_PRO_ARTICLE:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_PRO_ARTICLE_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_STANCES_AGAINST_ARTICLE:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_AGAINST_ARTICLE_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_STANCES_NEUTRAL_ARTICLE:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_NEUTRAL_ARTICLE_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($module, $props);
    }
}



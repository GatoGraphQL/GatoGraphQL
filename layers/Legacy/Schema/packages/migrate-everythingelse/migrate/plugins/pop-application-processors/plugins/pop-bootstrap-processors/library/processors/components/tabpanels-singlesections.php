<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Module_Processor_SingleSectionTabPanelComponents extends PoP_Module_Processor_SingleSectionTabPanelComponentsBase
{
    public const MODULE_TABPANEL_SINGLERELATEDCONTENT = 'tabpanel-singlerelatedcontent';
    public const MODULE_TABPANEL_SINGLEAUTHORS = 'tabpanel-singleauthors';
    public const MODULE_TABPANEL_SINGLERECOMMENDEDBY = 'tabpanel-singlerecommendedby';
    public const MODULE_TABPANEL_SINGLEUPVOTEDBY = 'tabpanel-singleupvotedby';
    public const MODULE_TABPANEL_SINGLEDOWNVOTEDBY = 'tabpanel-singledownvotedby';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_SINGLERELATEDCONTENT],
            [self::class, self::MODULE_TABPANEL_SINGLEAUTHORS],
            [self::class, self::MODULE_TABPANEL_SINGLERECOMMENDEDBY],
            [self::class, self::MODULE_TABPANEL_SINGLEUPVOTEDBY],
            [self::class, self::MODULE_TABPANEL_SINGLEDOWNVOTEDBY],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_SINGLEAUTHORS:
            case self::MODULE_TABPANEL_SINGLERECOMMENDEDBY:
            case self::MODULE_TABPANEL_SINGLEUPVOTEDBY:
            case self::MODULE_TABPANEL_SINGLEDOWNVOTEDBY:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLEUSERS);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_SINGLERELATEDCONTENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW],
                        [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW],
                        [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS],
                        [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL],
                        [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_SINGLEAUTHORS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW],
                        [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS],
                        [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL],
                        [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_SINGLERECOMMENDEDBY:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_SINGLEUPVOTEDBY:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_SINGLEDOWNVOTEDBY:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST],
                    )
                );
                break;
        }

        // Allow Events Manager to add the Map format
        $ret = HooksAPIFacade::getInstance()->applyFilters('PoP_Module_Processor_SingleSectionTabPanelComponents:modules', $ret, $module);

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_SINGLERELATEDCONTENT:
                $ret = array(
                    [
                        'header-submodule' => [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW],
                            [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS],
                            [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL],
                            [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_SINGLEAUTHORS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS],
                        'subheader-submodules' =>  array(
                            [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS],
                            [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL],
                            [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_SINGLERECOMMENDEDBY:
                $ret = array(
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS],
                        'subheader-submodules' =>  array(
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_SINGLEUPVOTEDBY:
                $ret = array(
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS],
                        'subheader-submodules' =>  array(
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_SINGLEDOWNVOTEDBY:
                $ret = array(
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS],
                        'subheader-submodules' =>  array(
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST],
                        ),
                    ],
                );
                break;
        }

        if ($ret) {
            return HooksAPIFacade::getInstance()->applyFilters('PoP_Module_Processor_SingleSectionTabPanelComponents:panel_headers', $ret, $module);
        }

        return parent::getPanelHeaders($module, $props);
    }
}



<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Module_Processor_AuthorSectionTabPanelComponents extends PoP_Module_Processor_AuthorSectionTabPanelComponentsBase
{
    public const MODULE_TABPANEL_AUTHORCONTENT = 'tabpanel-authorcontent';
    public const MODULE_TABPANEL_AUTHORPOSTS = 'tabpanel-authorposts';
    public const MODULE_TABPANEL_AUTHORFOLLOWERS = 'tabpanel-authorfollowers';
    public const MODULE_TABPANEL_AUTHORFOLLOWINGUSERS = 'tabpanel-authorfollowingusers';
    public const MODULE_TABPANEL_AUTHORSUBSCRIBEDTOTAGS = 'tabpanel-authorsubscribedtotags';
    public const MODULE_TABPANEL_AUTHORRECOMMENDEDPOSTS = 'tabpanel-authorrecommendedposts';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_AUTHORCONTENT],
            [self::class, self::MODULE_TABPANEL_AUTHORPOSTS],
            [self::class, self::MODULE_TABPANEL_AUTHORFOLLOWERS],
            [self::class, self::MODULE_TABPANEL_AUTHORFOLLOWINGUSERS],
            [self::class, self::MODULE_TABPANEL_AUTHORSUBSCRIBEDTOTAGS],
            [self::class, self::MODULE_TABPANEL_AUTHORRECOMMENDEDPOSTS],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_AUTHORFOLLOWERS:
            case self::MODULE_TABPANEL_AUTHORFOLLOWINGUSERS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORUSERS);

            case self::MODULE_TABPANEL_AUTHORSUBSCRIBEDTOTAGS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORTAGS);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_AUTHORCONTENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_AUTHORPOSTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_AUTHORFOLLOWERS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_AUTHORFOLLOWINGUSERS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_AUTHORSUBSCRIBEDTOTAGS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_AUTHORRECOMMENDEDPOSTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],
                    )
                );
                break;
        }

        // Allow Events Manager to add the Map format
        $ret = \PoP\Root\App::getHookManager()->applyFilters('PoP_Module_Processor_AuthorSectionTabPanelComponents:modules', $ret, $module);

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_AUTHORCONTENT:
                $ret = array(
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW],
                        ],
                    ],
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST],
                        'subheader-submodules' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORCONTENT_SCROLL_LIST],
                        ],
                    ],
                );
                break;

            case self::MODULE_TABPANEL_AUTHORPOSTS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW],
                        ],
                    ],
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS],
                        'subheader-submodules' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORPOSTS_SCROLL_LIST],
                        ],
                    ],
                );
                break;

            case self::MODULE_TABPANEL_AUTHORFOLLOWERS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS],
                        'subheader-submodules' => [
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST],
                        ],
                    ],
                );
                break;

            case self::MODULE_TABPANEL_AUTHORFOLLOWINGUSERS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
                        'subheader-submodules' => [
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST],
                        ],
                    ],
                );
                break;

            case self::MODULE_TABPANEL_AUTHORSUBSCRIBEDTOTAGS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS],
                    ],
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST],
                    ],
                );
                break;

            case self::MODULE_TABPANEL_AUTHORRECOMMENDEDPOSTS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' => [
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW],
                        ],
                    ],
                    [
                        'header-submodule' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],
                        'subheader-submodules' => [
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],
                        ],
                    ],
                );
                break;
        }

        if ($ret) {
            return \PoP\Root\App::getHookManager()->applyFilters('PoP_Module_Processor_AuthorSectionTabPanelComponents:panel_headers', $ret, $module);
        }

        return parent::getPanelHeaders($module, $props);
    }
}



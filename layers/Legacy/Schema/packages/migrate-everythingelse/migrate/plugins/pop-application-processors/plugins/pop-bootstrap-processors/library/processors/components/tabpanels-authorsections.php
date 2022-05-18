<?php

class PoP_Module_Processor_AuthorSectionTabPanelComponents extends PoP_Module_Processor_AuthorSectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_AUTHORCONTENT = 'tabpanel-authorcontent';
    public final const COMPONENT_TABPANEL_AUTHORPOSTS = 'tabpanel-authorposts';
    public final const COMPONENT_TABPANEL_AUTHORFOLLOWERS = 'tabpanel-authorfollowers';
    public final const COMPONENT_TABPANEL_AUTHORFOLLOWINGUSERS = 'tabpanel-authorfollowingusers';
    public final const COMPONENT_TABPANEL_AUTHORSUBSCRIBEDTOTAGS = 'tabpanel-authorsubscribedtotags';
    public final const COMPONENT_TABPANEL_AUTHORRECOMMENDEDPOSTS = 'tabpanel-authorrecommendedposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_AUTHORCONTENT],
            [self::class, self::COMPONENT_TABPANEL_AUTHORPOSTS],
            [self::class, self::COMPONENT_TABPANEL_AUTHORFOLLOWERS],
            [self::class, self::COMPONENT_TABPANEL_AUTHORFOLLOWINGUSERS],
            [self::class, self::COMPONENT_TABPANEL_AUTHORSUBSCRIBEDTOTAGS],
            [self::class, self::COMPONENT_TABPANEL_AUTHORRECOMMENDEDPOSTS],
        );
    }

    protected function getDefaultActivepanelFormat(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORFOLLOWERS:
            case self::COMPONENT_TABPANEL_AUTHORFOLLOWINGUSERS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORUSERS);

            case self::COMPONENT_TABPANEL_AUTHORSUBSCRIBEDTOTAGS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORTAGS);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORCONTENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCONTENT_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORPOSTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPOSTS_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORFOLLOWERS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORFOLLOWINGUSERS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORSUBSCRIBEDTOTAGS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORRECOMMENDEDPOSTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],
                    )
                );
                break;
        }

        // Allow Events Manager to add the Map format
        $ret = \PoP\Root\App::applyFilters('PoP_Module_Processor_AuthorSectionTabPanelComponents:modules', $ret, $component);

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORCONTENT:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCONTENT_SCROLL_SIMPLEVIEW],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCONTENT_SCROLL_FULLVIEW],
                        ],
                    ],
                    [
                        'header-subcomponent' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCONTENT_SCROLL_LIST],
                        'subheader-subcomponents' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCONTENT_SCROLL_DETAILS],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCONTENT_SCROLL_THUMBNAIL],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORCONTENT_SCROLL_LIST],
                        ],
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORPOSTS:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPOSTS_SCROLL_SIMPLEVIEW],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPOSTS_SCROLL_FULLVIEW],
                        ],
                    ],
                    [
                        'header-subcomponent' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS],
                        'subheader-subcomponents' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPOSTS_SCROLL_DETAILS],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPOSTS_SCROLL_THUMBNAIL],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPOSTS_SCROLL_LIST],
                        ],
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORFOLLOWERS:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS],
                        'subheader-subcomponents' => [
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLL_DETAILS],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLL_THUMBNAIL],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWERS_SCROLL_LIST],
                        ],
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORFOLLOWINGUSERS:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
                        'subheader-subcomponents' => [
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_DETAILS],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_THUMBNAIL],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORFOLLOWINGUSERS_SCROLL_LIST],
                        ],
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORSUBSCRIBEDTOTAGS:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_DETAILS],
                    ],
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSUBSCRIBEDTOTAGS_SCROLL_LIST],
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORRECOMMENDEDPOSTS:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' => [
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_SIMPLEVIEW],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_FULLVIEW],
                        ],
                    ],
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],
                        'subheader-subcomponents' => [
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_DETAILS],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_THUMBNAIL],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORRECOMMENDEDPOSTS_SCROLL_LIST],
                        ],
                    ],
                );
                break;
        }

        if ($ret) {
            return \PoP\Root\App::applyFilters('PoP_Module_Processor_AuthorSectionTabPanelComponents:panel_headers', $ret, $component);
        }

        return parent::getPanelHeaders($component, $props);
    }
}



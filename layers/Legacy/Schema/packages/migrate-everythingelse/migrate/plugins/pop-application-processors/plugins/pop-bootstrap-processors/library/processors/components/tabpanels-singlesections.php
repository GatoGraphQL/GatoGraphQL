<?php

class PoP_Module_Processor_SingleSectionTabPanelComponents extends PoP_Module_Processor_SingleSectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_SINGLERELATEDCONTENT = 'tabpanel-singlerelatedcontent';
    public final const COMPONENT_TABPANEL_SINGLEAUTHORS = 'tabpanel-singleauthors';
    public final const COMPONENT_TABPANEL_SINGLERECOMMENDEDBY = 'tabpanel-singlerecommendedby';
    public final const COMPONENT_TABPANEL_SINGLEUPVOTEDBY = 'tabpanel-singleupvotedby';
    public final const COMPONENT_TABPANEL_SINGLEDOWNVOTEDBY = 'tabpanel-singledownvotedby';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_SINGLERELATEDCONTENT],
            [self::class, self::COMPONENT_TABPANEL_SINGLEAUTHORS],
            [self::class, self::COMPONENT_TABPANEL_SINGLERECOMMENDEDBY],
            [self::class, self::COMPONENT_TABPANEL_SINGLEUPVOTEDBY],
            [self::class, self::COMPONENT_TABPANEL_SINGLEDOWNVOTEDBY],
        );
    }

    protected function getDefaultActivepanelFormat(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_SINGLEAUTHORS:
            case self::COMPONENT_TABPANEL_SINGLERECOMMENDEDBY:
            case self::COMPONENT_TABPANEL_SINGLEUPVOTEDBY:
            case self::COMPONENT_TABPANEL_SINGLEDOWNVOTEDBY:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLEUSERS);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubcomponents(array $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_SINGLERELATEDCONTENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW],
                        [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW],
                        [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS],
                        [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL],
                        [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLEAUTHORS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW],
                        [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS],
                        [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL],
                        [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLERECOMMENDEDBY:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLEUPVOTEDBY:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLEDOWNVOTEDBY:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL],
                        [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST],
                    )
                );
                break;
        }

        // Allow Events Manager to add the Map format
        $ret = \PoP\Root\App::applyFilters('PoP_Module_Processor_SingleSectionTabPanelComponents:modules', $ret, $component);

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_SINGLERELATEDCONTENT:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_SIMPLEVIEW],
                            [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_DETAILS],
                            [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_THUMBNAIL],
                            [PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::class, PoP_RelatedPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDCONTENT_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLEAUTHORS:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS],
                        'subheader-subcomponents' =>  array(
                            [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS],
                            [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL],
                            [PoP_Module_Processor_CustomSectionDataloads::class, PoP_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEAUTHORS_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLERECOMMENDEDBY:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS],
                        'subheader-subcomponents' =>  array(
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_DETAILS],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_THUMBNAIL],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERECOMMENDEDBY_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLEUPVOTEDBY:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS],
                        'subheader-subcomponents' =>  array(
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLL_DETAILS],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLL_THUMBNAIL],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEUPVOTEDBY_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLEDOWNVOTEDBY:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS],
                        'subheader-subcomponents' =>  array(
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_DETAILS],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_THUMBNAIL],
                            [PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::class, PoP_SocialNetwork_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLEDOWNVOTEDBY_SCROLL_LIST],
                        ),
                    ],
                );
                break;
        }

        if ($ret) {
            return \PoP\Root\App::applyFilters('PoP_Module_Processor_SingleSectionTabPanelComponents:panel_headers', $ret, $component);
        }

        return parent::getPanelHeaders($component, $props);
    }
}



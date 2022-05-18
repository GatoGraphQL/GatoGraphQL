<?php

class PoP_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_SEARCHCONTENT = 'tabpanel-searchcontent';
    public final const MODULE_TABPANEL_CONTENT = 'tabpanel-content';
    public final const MODULE_TABPANEL_POSTS = 'tabpanel-posts';
    public final const MODULE_TABPANEL_SEARCHUSERS = 'tabpanel-searchusers';
    public final const MODULE_TABPANEL_USERS = 'tabpanel-users';
    public final const MODULE_TABPANEL_MYCONTENT = 'tabpanel-mycontent';
    public final const MODULE_TABPANEL_MYPOSTS = 'tabpanel-myposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_SEARCHCONTENT],
            [self::class, self::COMPONENT_TABPANEL_CONTENT],
            [self::class, self::COMPONENT_TABPANEL_POSTS],
            
            [self::class, self::COMPONENT_TABPANEL_SEARCHUSERS],
            [self::class, self::COMPONENT_TABPANEL_USERS],
            
            [self::class, self::COMPONENT_TABPANEL_MYCONTENT],
            [self::class, self::COMPONENT_TABPANEL_MYPOSTS],
        );
    }

    protected function getDefaultActivepanelFormat(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_SEARCHUSERS:
            case self::COMPONENT_TABPANEL_USERS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);

            case self::COMPONENT_TABPANEL_MYCONTENT:
            case self::COMPONENT_TABPANEL_MYPOSTS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);
        }
        
        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_CONTENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_CONTENT_SCROLL_SIMPLEVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_CONTENT_SCROLL_FULLVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_CONTENT_SCROLL_DETAILS],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_CONTENT_SCROLL_THUMBNAIL],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_CONTENT_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_POSTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_POSTS_SCROLL_SIMPLEVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_POSTS_SCROLL_FULLVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_POSTS_SCROLL_DETAILS],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_POSTS_SCROLL_THUMBNAIL],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_POSTS_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_SEARCHCONTENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHCONTENT_SCROLL_FULLVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHCONTENT_SCROLL_DETAILS],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHCONTENT_SCROLL_THUMBNAIL],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHCONTENT_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_USERS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_USERS_SCROLL_FULLVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_USERS_SCROLL_DETAILS],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_USERS_SCROLL_THUMBNAIL],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_USERS_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_SEARCHUSERS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_SCROLL_FULLVIEW],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_SCROLL_DETAILS],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_SCROLL_THUMBNAIL],
                        [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_MYCONTENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_ContentCreation_Module_Processor_MySectionDataloads::class, PoP_ContentCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYCONTENT_TABLE_EDIT],
                        [PoP_ContentCreation_Module_Processor_MySectionDataloads::class, PoP_ContentCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW],
                        [PoP_ContentCreation_Module_Processor_MySectionDataloads::class, PoP_ContentCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYCONTENT_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_MYPOSTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_PostsCreation_Module_Processor_MySectionDataloads::class, PoP_PostsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPOSTS_TABLE_EDIT],
                        [PoP_PostsCreation_Module_Processor_MySectionDataloads::class, PoP_PostsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
                        [PoP_PostsCreation_Module_Processor_MySectionDataloads::class, PoP_PostsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;
        }

        // Allow Events Manager to add the Map format
        $ret = \PoP\Root\App::applyFilters('PoP_Module_Processor_SectionTabPanelComponents:modules', $ret, $component);

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_CONTENT:
                $ret = array(
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_CONTENT_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_CONTENT_SCROLL_SIMPLEVIEW],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_CONTENT_SCROLL_FULLVIEW],
                        ],
                    ],
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_CONTENT_SCROLL_LIST],
                        'subheader-submodules' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_CONTENT_SCROLL_DETAILS],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_CONTENT_SCROLL_THUMBNAIL],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_CONTENT_SCROLL_LIST],
                        ],
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_POSTS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_POSTS_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_POSTS_SCROLL_SIMPLEVIEW],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_POSTS_SCROLL_FULLVIEW],
                        ],
                    ],
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_POSTS_SCROLL_LIST],
                        'subheader-submodules' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_POSTS_SCROLL_DETAILS],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_POSTS_SCROLL_THUMBNAIL],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_POSTS_SCROLL_LIST],
                        ],
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_SEARCHCONTENT:
                $ret = array(
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHCONTENT_SCROLL_SIMPLEVIEW],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHCONTENT_SCROLL_FULLVIEW],
                        ],
                    ],
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHCONTENT_SCROLL_LIST],
                        'subheader-submodules' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHCONTENT_SCROLL_DETAILS],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHCONTENT_SCROLL_THUMBNAIL],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHCONTENT_SCROLL_LIST],
                        ],
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_USERS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_USERS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_USERS_SCROLL_DETAILS],
                        'subheader-submodules' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_USERS_SCROLL_DETAILS],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_USERS_SCROLL_THUMBNAIL],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_USERS_SCROLL_LIST],
                        ],
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_SEARCHUSERS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_SCROLL_DETAILS],
                        'subheader-submodules' => [
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_SCROLL_DETAILS],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_SCROLL_THUMBNAIL],
                            [PoP_Blog_Module_Processor_CustomSectionDataloads::class, PoP_Blog_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_SCROLL_LIST],
                        ],
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_MYCONTENT:
                $ret = array(
                    [
                        'header-submodule' => [PoP_ContentCreation_Module_Processor_MySectionDataloads::class, PoP_ContentCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYCONTENT_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [PoP_ContentCreation_Module_Processor_MySectionDataloads::class, PoP_ContentCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' => [
                            [PoP_ContentCreation_Module_Processor_MySectionDataloads::class, PoP_ContentCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW],
                            [PoP_ContentCreation_Module_Processor_MySectionDataloads::class, PoP_ContentCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYCONTENT_SCROLL_FULLVIEWPREVIEW],
                        ],
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_MYPOSTS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_PostsCreation_Module_Processor_MySectionDataloads::class, PoP_PostsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPOSTS_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [PoP_PostsCreation_Module_Processor_MySectionDataloads::class, PoP_PostsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' => [
                            [PoP_PostsCreation_Module_Processor_MySectionDataloads::class, PoP_PostsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
                            [PoP_PostsCreation_Module_Processor_MySectionDataloads::class, PoP_PostsCreation_Module_Processor_MySectionDataloads::COMPONENT_DATALOAD_MYPOSTS_SCROLL_FULLVIEWPREVIEW],
                        ],
                    ],
                );
                break;
        }

        if ($ret) {
            return \PoP\Root\App::applyFilters('PoP_Module_Processor_SectionTabPanelComponents:panel_headers', $ret, $component);
        }

        return parent::getPanelHeaders($component, $props);
    }
}



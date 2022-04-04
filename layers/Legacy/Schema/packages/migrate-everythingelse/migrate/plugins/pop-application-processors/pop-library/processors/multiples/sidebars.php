<?php

class PoP_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_SECTION_CONTENT_SIDEBAR = 'multiple-section-content-sidebar';
    public final const MODULE_MULTIPLE_SECTION_POSTS_SIDEBAR = 'multiple-section-posts-sidebar';
    public final const MODULE_MULTIPLE_SECTION_CATEGORYPOSTS_SIDEBAR = 'multiple-section-categoryposts-sidebar';
    public final const MODULE_MULTIPLE_SECTION_USERS_SIDEBAR = 'multiple-section-users-sidebar';
    public final const MODULE_MULTIPLE_SECTION_TRENDINGTAGS_SIDEBAR = 'multiple-section-trendingtags-sidebar';
    public final const MODULE_MULTIPLE_SECTION_TAGS_SIDEBAR = 'multiple-section-tags-sidebar';
    public final const MODULE_MULTIPLE_SINGLEPAGE_ABOUT_SIDEBAR = 'multiple-singlepage-about-sidebar';
    public final const MODULE_MULTIPLE_SINGLEPAGE_SIDEBAR = 'multiple-singlepage-sidebar';
    public final const MODULE_MULTIPLE_SECTION_MYCONTENT_SIDEBAR = 'multiple-section-mycontent-sidebar';
    public final const MODULE_MULTIPLE_SECTION_MYPOSTS_SIDEBAR = 'multiple-section-myposts-sidebar';
    public final const MODULE_MULTIPLE_SECTION_MYCATEGORYPOSTS_SIDEBAR = 'multiple-section-mycategoryposts-sidebar';
    public final const MODULE_MULTIPLE_TAG_MAINCONTENT_SIDEBAR = 'multiple-tag-mainallcontent-sidebar';
    public final const MODULE_MULTIPLE_TAG_CONTENT_SIDEBAR = 'multiple-tag-content-sidebar';
    public final const MODULE_MULTIPLE_TAG_POSTS_SIDEBAR = 'multiple-tag-posts-sidebar';
    public final const MODULE_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR = 'multiple-tag-categoryposts-sidebar';
    public final const MODULE_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR = 'multiple-tag-subscribers-sidebar';
    public final const MODULE_MULTIPLE_SINGLE_POST_SIDEBAR = 'multiple-single-post-sidebar';
    public final const MODULE_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR = 'multiple-single-post-relatedcontentsidebar';
    public final const MODULE_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR = 'multiple-single-post-postauthorssidebar';
    public final const MODULE_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR = 'multiple-single-post-recommendedbysidebar';
    public final const MODULE_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR = 'multiple-homesection-content-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTION_CONTENT_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_POSTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_CATEGORYPOSTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_USERS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_TRENDINGTAGS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_TAGS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_MYCONTENT_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_MYPOSTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_MYCATEGORYPOSTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLEPAGE_ABOUT_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLEPAGE_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_TAG_MAINCONTENT_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_TAG_CONTENT_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_TAG_POSTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLE_POST_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR],
            [self::class, self::MODULE_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
         // Add also the filter block for the Single Related Content, etc
            case self::MODULE_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR:
                $filters = array(
                    self::MODULE_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_CONTENT_SIDEBAR],
                    self::MODULE_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_USERS_NOFILTER_SIDEBAR],
                    self::MODULE_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_USERS_SIDEBAR],
                );
                $ret[] = $filters[$module[1]];
                $ret[] = [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_POST_SIDEBAR];
                break;

            case self::MODULE_MULTIPLE_TAG_MAINCONTENT_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_CONTENT_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_POSTS_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR:
                $filters = array(
                    self::MODULE_MULTIPLE_TAG_MAINCONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_TAGSECTIONINNER_MAINCONTENT_SIDEBAR],
                    self::MODULE_MULTIPLE_TAG_CONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_TAGSECTIONINNER_CONTENT_SIDEBAR],
                    self::MODULE_MULTIPLE_TAG_POSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_TAGSECTIONINNER_POSTS_SIDEBAR],
                    self::MODULE_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_TAGSECTIONINNER_CATEGORYPOSTS_SIDEBAR],
                    self::MODULE_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_USERS_SIDEBAR],
                );
                $ret[] = $filters[$module[1]];
                $ret[] = [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_TAG_SIDEBAR];
                break;

            default:
                $inners = array(
                    self::MODULE_MULTIPLE_SECTION_CONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_CONTENT_SIDEBAR],
                    self::MODULE_MULTIPLE_SECTION_POSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_POSTS_SIDEBAR],
                    self::MODULE_MULTIPLE_SECTION_CATEGORYPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_CATEGORYPOSTS_SIDEBAR],
                    self::MODULE_MULTIPLE_SECTION_USERS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_USERS_SIDEBAR],
                    self::MODULE_MULTIPLE_SECTION_TRENDINGTAGS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_TRENDINGTAGS_SIDEBAR],
                    self::MODULE_MULTIPLE_SECTION_TAGS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_TAGS_SIDEBAR],
                    self::MODULE_MULTIPLE_SECTION_MYCONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_MYCONTENT_SIDEBAR],
                    self::MODULE_MULTIPLE_SECTION_MYPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_MYPOSTS_SIDEBAR],
                    self::MODULE_MULTIPLE_SECTION_MYCATEGORYPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_SECTIONINNER_MYCATEGORYPOSTS_SIDEBAR],
                    self::MODULE_MULTIPLE_SINGLEPAGE_ABOUT_SIDEBAR => [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_SIDEBAR_ABOUT],
                    self::MODULE_MULTIPLE_SINGLE_POST_SIDEBAR => [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_POST_SIDEBAR],
                    self::MODULE_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_HOMESECTIONINNER_CONTENT_SIDEBAR],
                );
                if ($inner = $inners[$module[1]] ?? null) {
                    $ret[] = $inner;
                }
                break;
        }

        return $ret;
    }

    public function getScreen(array $module)
    {
        $screens = array(
            self::MODULE_MULTIPLE_SECTION_CONTENT_SIDEBAR => POP_SCREEN_SECTION,
            self::MODULE_MULTIPLE_SECTION_POSTS_SIDEBAR => POP_SCREEN_SECTION,
            self::MODULE_MULTIPLE_SECTION_CATEGORYPOSTS_SIDEBAR => POP_SCREEN_SECTION,
            self::MODULE_MULTIPLE_SECTION_USERS_SIDEBAR => POP_SCREEN_USERS,
            self::MODULE_MULTIPLE_SECTION_TRENDINGTAGS_SIDEBAR => POP_SCREEN_TAGS,
            self::MODULE_MULTIPLE_SECTION_TAGS_SIDEBAR => POP_SCREEN_TAGS,
            self::MODULE_MULTIPLE_SECTION_MYCONTENT_SIDEBAR => POP_SCREEN_MYCONTENT,
            self::MODULE_MULTIPLE_SECTION_MYPOSTS_SIDEBAR => POP_SCREEN_MYCONTENT,
            self::MODULE_MULTIPLE_SECTION_MYCATEGORYPOSTS_SIDEBAR => POP_SCREEN_MYCONTENT,
            self::MODULE_MULTIPLE_SINGLEPAGE_ABOUT_SIDEBAR => POP_SCREEN_INFORMATIONPAGE,
            self::MODULE_MULTIPLE_SINGLEPAGE_SIDEBAR => POP_SCREEN_INFORMATIONPAGE,
            self::MODULE_MULTIPLE_TAG_MAINCONTENT_SIDEBAR => POP_SCREEN_TAGSECTION,
            self::MODULE_MULTIPLE_TAG_CONTENT_SIDEBAR => POP_SCREEN_TAGSECTION,
            self::MODULE_MULTIPLE_TAG_POSTS_SIDEBAR => POP_SCREEN_TAGSECTION,
            self::MODULE_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR => POP_SCREEN_TAGSECTION,
            self::MODULE_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR => POP_SCREEN_TAGUSERS,
            self::MODULE_MULTIPLE_SINGLE_POST_SIDEBAR => POP_SCREEN_SINGLE,
            self::MODULE_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR => POP_SCREEN_SINGLESECTION,
            self::MODULE_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR => POP_SCREEN_SINGLEUSERS,
            self::MODULE_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR => POP_SCREEN_SINGLEUSERS,
            self::MODULE_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR => POP_SCREEN_HOMESECTION,
        );
        if ($screen = $screens[$module[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTION_CONTENT_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_POSTS_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_CATEGORYPOSTS_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_USERS_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_TRENDINGTAGS_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_TAGS_SIDEBAR:
            case self::MODULE_MULTIPLE_SINGLEPAGE_ABOUT_SIDEBAR:
            case self::MODULE_MULTIPLE_SINGLEPAGE_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_MAINCONTENT_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_CONTENT_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_POSTS_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_POST_SIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR:
            case self::MODULE_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;

            case self::MODULE_MULTIPLE_SECTION_MYCONTENT_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_MYPOSTS_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_MYCATEGORYPOSTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTWRITE;
        }

        return parent::getScreengroup($module);
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_MAINCONTENT_SIDEBAR:
                if ($module == [self::class, self::MODULE_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR]) {
                     // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
                    $target_modules = array(
                        '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-home > .blocksection-extensions > .pop-block.withfilter' => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_HOMESECTIONINNER_CONTENT_SIDEBAR],
                    );
                } elseif ($module == [self::class, self::MODULE_MULTIPLE_TAG_MAINCONTENT_SIDEBAR]) {
                     // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
                    $target_modules = array(
                        '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-tag > .blocksection-extensions > .pop-block.withfilter' => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_TAGSECTIONINNER_MAINCONTENT_SIDEBAR],
                        '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-tag > .blocksection-extensions > .pop-block.withfilter' => [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_TAG_SIDEBAR],
                    );
                }
                foreach ($target_modules as $target => $submodule) {
                     // Make the block be collapsible, open it when the main feed is reached, with waypoints
                    $this->appendProp(array($submodule), $props, 'class', 'collapse');
                    $this->mergeProp(
                        array($submodule),
                        $props,
                        'params',
                        array(
                            'data-collapse-target' => $target
                        )
                    );
                    $this->mergeJsmethodsProp(array($submodule), $props, array('waypointsToggleCollapse'));
                }
                break;

            case self::MODULE_MULTIPLE_SINGLE_POST_SIDEBAR:
                $inners = array(
                    self::MODULE_MULTIPLE_SINGLE_POST_SIDEBAR => [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_POST_SIDEBAR],
                );
                $submodule = $inners[$module[1]];

                // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
                $mainblock_taget = '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-singlepost > .blocksection-extensions > .pop-block > .blocksection-inners .content-single';

                // Make the block be collapsible, open it when the main feed is reached, with waypoints
                $this->appendProp(array($submodule), $props, 'class', 'collapse');
                $this->mergeProp(
                    array($submodule),
                    $props,
                    'params',
                    array(
                        'data-collapse-target' => $mainblock_taget
                    )
                );
                $this->mergeJsmethodsProp(array($submodule), $props, array('waypointsToggleCollapse'));
                break;
        }

        parent::initWebPlatformModelProps($module, $props);
    }
}



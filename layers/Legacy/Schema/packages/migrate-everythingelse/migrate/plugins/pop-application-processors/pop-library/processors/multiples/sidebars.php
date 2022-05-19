<?php

class PoP_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTION_CONTENT_SIDEBAR = 'multiple-section-content-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_POSTS_SIDEBAR = 'multiple-section-posts-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_CATEGORYPOSTS_SIDEBAR = 'multiple-section-categoryposts-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_USERS_SIDEBAR = 'multiple-section-users-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_TRENDINGTAGS_SIDEBAR = 'multiple-section-trendingtags-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_TAGS_SIDEBAR = 'multiple-section-tags-sidebar';
    public final const COMPONENT_MULTIPLE_SINGLEPAGE_ABOUT_SIDEBAR = 'multiple-singlepage-about-sidebar';
    public final const COMPONENT_MULTIPLE_SINGLEPAGE_SIDEBAR = 'multiple-singlepage-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_MYCONTENT_SIDEBAR = 'multiple-section-mycontent-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_MYPOSTS_SIDEBAR = 'multiple-section-myposts-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_MYCATEGORYPOSTS_SIDEBAR = 'multiple-section-mycategoryposts-sidebar';
    public final const COMPONENT_MULTIPLE_TAG_MAINCONTENT_SIDEBAR = 'multiple-tag-mainallcontent-sidebar';
    public final const COMPONENT_MULTIPLE_TAG_CONTENT_SIDEBAR = 'multiple-tag-content-sidebar';
    public final const COMPONENT_MULTIPLE_TAG_POSTS_SIDEBAR = 'multiple-tag-posts-sidebar';
    public final const COMPONENT_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR = 'multiple-tag-categoryposts-sidebar';
    public final const COMPONENT_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR = 'multiple-tag-subscribers-sidebar';
    public final const COMPONENT_MULTIPLE_SINGLE_POST_SIDEBAR = 'multiple-single-post-sidebar';
    public final const COMPONENT_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR = 'multiple-single-post-relatedcontentsidebar';
    public final const COMPONENT_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR = 'multiple-single-post-postauthorssidebar';
    public final const COMPONENT_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR = 'multiple-single-post-recommendedbysidebar';
    public final const COMPONENT_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR = 'multiple-homesection-content-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SECTION_CONTENT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTION_POSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTION_CATEGORYPOSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTION_USERS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTION_TRENDINGTAGS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTION_TAGS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTION_MYCONTENT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTION_MYPOSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTION_MYCATEGORYPOSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SINGLEPAGE_ABOUT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SINGLEPAGE_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_TAG_MAINCONTENT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_TAG_CONTENT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_TAG_POSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SINGLE_POST_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR],
        );
    }

    public function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component[1]) {
         // Add also the filter block for the Single Related Content, etc
            case self::COMPONENT_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR:
            case self::COMPONENT_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR:
            case self::COMPONENT_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR:
                $filters = array(
                    self::COMPONENT_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_CONTENT_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_USERS_NOFILTER_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_USERS_SIDEBAR],
                );
                $ret[] = $filters[$component[1]];
                $ret[] = [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_SINGLE_POST_SIDEBAR];
                break;

            case self::COMPONENT_MULTIPLE_TAG_MAINCONTENT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_TAG_CONTENT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_TAG_POSTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR:
                $filters = array(
                    self::COMPONENT_MULTIPLE_TAG_MAINCONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_TAGSECTIONINNER_MAINCONTENT_SIDEBAR],
                    self::COMPONENT_MULTIPLE_TAG_CONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_TAGSECTIONINNER_CONTENT_SIDEBAR],
                    self::COMPONENT_MULTIPLE_TAG_POSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_TAGSECTIONINNER_POSTS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_TAGSECTIONINNER_CATEGORYPOSTS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_USERS_SIDEBAR],
                );
                $ret[] = $filters[$component[1]];
                $ret[] = [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_TAG_SIDEBAR];
                break;

            default:
                $inners = array(
                    self::COMPONENT_MULTIPLE_SECTION_CONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_CONTENT_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SECTION_POSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_POSTS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SECTION_CATEGORYPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_CATEGORYPOSTS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SECTION_USERS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_USERS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SECTION_TRENDINGTAGS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_TRENDINGTAGS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SECTION_TAGS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_TAGS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SECTION_MYCONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_MYCONTENT_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SECTION_MYPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_MYPOSTS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SECTION_MYCATEGORYPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_SECTIONINNER_MYCATEGORYPOSTS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SINGLEPAGE_ABOUT_SIDEBAR => [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::COMPONENT_MULTIPLE_MENU_SIDEBAR_ABOUT],
                    self::COMPONENT_MULTIPLE_SINGLE_POST_SIDEBAR => [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_SINGLE_POST_SIDEBAR],
                    self::COMPONENT_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_HOMESECTIONINNER_CONTENT_SIDEBAR],
                );
                if ($inner = $inners[$component[1]] ?? null) {
                    $ret[] = $inner;
                }
                break;
        }

        return $ret;
    }

    public function getScreen(array $component)
    {
        $screens = array(
            self::COMPONENT_MULTIPLE_SECTION_CONTENT_SIDEBAR => POP_SCREEN_SECTION,
            self::COMPONENT_MULTIPLE_SECTION_POSTS_SIDEBAR => POP_SCREEN_SECTION,
            self::COMPONENT_MULTIPLE_SECTION_CATEGORYPOSTS_SIDEBAR => POP_SCREEN_SECTION,
            self::COMPONENT_MULTIPLE_SECTION_USERS_SIDEBAR => POP_SCREEN_USERS,
            self::COMPONENT_MULTIPLE_SECTION_TRENDINGTAGS_SIDEBAR => POP_SCREEN_TAGS,
            self::COMPONENT_MULTIPLE_SECTION_TAGS_SIDEBAR => POP_SCREEN_TAGS,
            self::COMPONENT_MULTIPLE_SECTION_MYCONTENT_SIDEBAR => POP_SCREEN_MYCONTENT,
            self::COMPONENT_MULTIPLE_SECTION_MYPOSTS_SIDEBAR => POP_SCREEN_MYCONTENT,
            self::COMPONENT_MULTIPLE_SECTION_MYCATEGORYPOSTS_SIDEBAR => POP_SCREEN_MYCONTENT,
            self::COMPONENT_MULTIPLE_SINGLEPAGE_ABOUT_SIDEBAR => POP_SCREEN_INFORMATIONPAGE,
            self::COMPONENT_MULTIPLE_SINGLEPAGE_SIDEBAR => POP_SCREEN_INFORMATIONPAGE,
            self::COMPONENT_MULTIPLE_TAG_MAINCONTENT_SIDEBAR => POP_SCREEN_TAGSECTION,
            self::COMPONENT_MULTIPLE_TAG_CONTENT_SIDEBAR => POP_SCREEN_TAGSECTION,
            self::COMPONENT_MULTIPLE_TAG_POSTS_SIDEBAR => POP_SCREEN_TAGSECTION,
            self::COMPONENT_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR => POP_SCREEN_TAGSECTION,
            self::COMPONENT_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR => POP_SCREEN_TAGUSERS,
            self::COMPONENT_MULTIPLE_SINGLE_POST_SIDEBAR => POP_SCREEN_SINGLE,
            self::COMPONENT_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR => POP_SCREEN_SINGLESECTION,
            self::COMPONENT_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR => POP_SCREEN_SINGLEUSERS,
            self::COMPONENT_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR => POP_SCREEN_SINGLEUSERS,
            self::COMPONENT_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR => POP_SCREEN_HOMESECTION,
        );
        if ($screen = $screens[$component[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SECTION_CONTENT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SECTION_POSTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SECTION_CATEGORYPOSTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SECTION_USERS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SECTION_TRENDINGTAGS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SECTION_TAGS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SINGLEPAGE_ABOUT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SINGLEPAGE_SIDEBAR:
            case self::COMPONENT_MULTIPLE_TAG_MAINCONTENT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_TAG_CONTENT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_TAG_POSTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_TAG_CATEGORYPOSTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_TAG_SUBSCRIBERS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SINGLE_POST_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR:
            case self::COMPONENT_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR:
            case self::COMPONENT_MULTIPLE_SINGLE_POST_RECOMMENDEDBYSIDEBAR:
            case self::COMPONENT_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;

            case self::COMPONENT_MULTIPLE_SECTION_MYCONTENT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SECTION_MYPOSTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SECTION_MYCATEGORYPOSTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTWRITE;
        }

        return parent::getScreengroup($component);
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_TAG_MAINCONTENT_SIDEBAR:
                if ($component == [self::class, self::COMPONENT_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR]) {
                     // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
                    $target_components = array(
                        '#'.POP_COMPONENTID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-home > .blocksection-extensions > .pop-block.withfilter' => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_HOMESECTIONINNER_CONTENT_SIDEBAR],
                    );
                } elseif ($component == [self::class, self::COMPONENT_MULTIPLE_TAG_MAINCONTENT_SIDEBAR]) {
                     // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
                    $target_components = array(
                        '#'.POP_COMPONENTID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-tag > .blocksection-extensions > .pop-block.withfilter' => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_TAGSECTIONINNER_MAINCONTENT_SIDEBAR],
                        '#'.POP_COMPONENTID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-tag > .blocksection-extensions > .pop-block.withfilter' => [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_TAG_SIDEBAR],
                    );
                }
                foreach ($target_components as $target => $subComponent) {
                     // Make the block be collapsible, open it when the main feed is reached, with waypoints
                    $this->appendProp(array($subComponent), $props, 'class', 'collapse');
                    $this->mergeProp(
                        array($subComponent),
                        $props,
                        'params',
                        array(
                            'data-collapse-target' => $target
                        )
                    );
                    $this->mergeJsmethodsProp(array($subComponent), $props, array('waypointsToggleCollapse'));
                }
                break;

            case self::COMPONENT_MULTIPLE_SINGLE_POST_SIDEBAR:
                $inners = array(
                    self::COMPONENT_MULTIPLE_SINGLE_POST_SIDEBAR => [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_SINGLE_POST_SIDEBAR],
                );
                $subComponent = $inners[$component[1]];

                // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
                $mainblock_taget = '#'.POP_COMPONENTID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-singlepost > .blocksection-extensions > .pop-block > .blocksection-inners .content-single';

                // Make the block be collapsible, open it when the main feed is reached, with waypoints
                $this->appendProp(array($subComponent), $props, 'class', 'collapse');
                $this->mergeProp(
                    array($subComponent),
                    $props,
                    'params',
                    array(
                        'data-collapse-target' => $mainblock_taget
                    )
                );
                $this->mergeJsmethodsProp(array($subComponent), $props, array('waypointsToggleCollapse'));
                break;
        }

        parent::initWebPlatformModelProps($component, $props);
    }
}



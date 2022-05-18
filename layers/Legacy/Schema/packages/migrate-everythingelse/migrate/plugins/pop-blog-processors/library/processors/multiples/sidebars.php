<?php
use PoP\ComponentModel\State\ApplicationState;

class PoP_Blog_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const MODULE_MULTIPLE_AUTHOR_SIDEBAR = 'multiple-author-sidebar';
    public final const MODULE_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR = 'multiple-authormaincontent-sidebar';
    public final const MODULE_MULTIPLE_AUTHORCONTENT_SIDEBAR = 'multiple-authorcontent-sidebar';
    public final const MODULE_MULTIPLE_AUTHORPOSTS_SIDEBAR = 'multiple-authorposts-sidebar';
    public final const MODULE_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR = 'multiple-authorcategoryposts-sidebar';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_AUTHOR_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_AUTHORCONTENT_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_AUTHORPOSTS_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
         // Add also the filter block for the Single Related Content, etc
            case self::COMPONENT_MULTIPLE_AUTHOR_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORCONTENT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORPOSTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $filters = array(
                    self::COMPONENT_MULTIPLE_AUTHOR_SIDEBAR => null,
                    self::COMPONENT_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_AUTHORSECTIONINNER_MAINCONTENT_SIDEBAR],
                    self::COMPONENT_MULTIPLE_AUTHORCONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_AUTHORSECTIONINNER_CONTENT_SIDEBAR],
                    self::COMPONENT_MULTIPLE_AUTHORPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_AUTHORSECTIONINNER_POSTS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::COMPONENT_MULTIPLE_AUTHORSECTIONINNER_CATEGORYPOSTS_SIDEBAR],
                );
                if ($filter = $filters[$component[1]] ?? null) {
                    $ret[] = $filter;
                }

                // Allow URE to add the Organization/Individual sidebars below
                $ret = \PoP\Root\App::applyFilters(
                    'PoP_UserCommunities_Module_Processor_SidebarMultiples:sidebar-layouts',
                    $ret,
                    $author,
                    $component
                );
                break;
        }

        return $ret;
    }

    public function getScreen(array $component)
    {
        $screens = array(
            self::COMPONENT_MULTIPLE_AUTHOR_SIDEBAR => POP_SCREEN_AUTHOR,
            self::COMPONENT_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR => POP_SCREEN_AUTHORSECTION,
            self::COMPONENT_MULTIPLE_AUTHORCONTENT_SIDEBAR => POP_SCREEN_AUTHORSECTION,
            self::COMPONENT_MULTIPLE_AUTHORPOSTS_SIDEBAR => POP_SCREEN_AUTHORSECTION,
            self::COMPONENT_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR => POP_SCREEN_AUTHORSECTION,
        );
        if ($screen = $screens[$component[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_AUTHOR_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORCONTENT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORPOSTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($component);
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR:
                $subComponents = array_diff(
                    $this->getSubComponents($component),
                    $this->getPermanentSubmodules($component)
                );
                foreach ($subComponents as $subComponent) {
                      // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
                    $mainblock_taget = '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-author > .blocksection-extensions > .pop-block.withfilter';

                    // Make the block be collapsible, open it when the main feed is reached, with waypoints
                    $this->appendProp([$subComponent], $props, 'class', 'collapse');
                    $this->mergeProp(
                        [$subComponent],
                        $props,
                        'params',
                        array(
                            'data-collapse-target' => $mainblock_taget
                        )
                    );
                    $this->mergeJsmethodsProp([$subComponent], $props, array('waypointsToggleCollapse'));
                }
                break;
        }

        parent::initWebPlatformModelProps($component, $props);
    }
}



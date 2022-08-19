<?php
use PoP\ComponentModel\State\ApplicationState;

class PoP_Blog_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_AUTHOR_SIDEBAR = 'multiple-author-sidebar';
    public final const COMPONENT_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR = 'multiple-authormaincontent-sidebar';
    public final const COMPONENT_MULTIPLE_AUTHORCONTENT_SIDEBAR = 'multiple-authorcontent-sidebar';
    public final const COMPONENT_MULTIPLE_AUTHORPOSTS_SIDEBAR = 'multiple-authorposts-sidebar';
    public final const COMPONENT_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR = 'multiple-authorcategoryposts-sidebar';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTIPLE_AUTHOR_SIDEBAR,
            self::COMPONENT_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR,
            self::COMPONENT_MULTIPLE_AUTHORCONTENT_SIDEBAR,
            self::COMPONENT_MULTIPLE_AUTHORPOSTS_SIDEBAR,
            self::COMPONENT_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR,
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
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
                if ($filter = $filters[$component->name] ?? null) {
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

    public function getScreen(\PoP\ComponentModel\Component\Component $component)
    {
        $screens = array(
            self::COMPONENT_MULTIPLE_AUTHOR_SIDEBAR => POP_SCREEN_AUTHOR,
            self::COMPONENT_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR => POP_SCREEN_AUTHORSECTION,
            self::COMPONENT_MULTIPLE_AUTHORCONTENT_SIDEBAR => POP_SCREEN_AUTHORSECTION,
            self::COMPONENT_MULTIPLE_AUTHORPOSTS_SIDEBAR => POP_SCREEN_AUTHORSECTION,
            self::COMPONENT_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR => POP_SCREEN_AUTHORSECTION,
        );
        if ($screen = $screens[$component->name] ?? null) {
            return $screen;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_AUTHOR_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORCONTENT_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORPOSTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($component);
    }

    public function initWebPlatformModelProps(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR:
                $subcomponents = array_diff(
                    $this->getSubcomponents($component),
                    $this->getPermanentSubcomponents($component)
                );
                foreach ($subcomponents as $subcomponent) {
                      // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
                    $mainblock_taget = '#'.POP_COMPONENTID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-author > .blocksection-extensions > .pop-block.withfilter';

                    // Make the block be collapsible, open it when the main feed is reached, with waypoints
                    $this->appendProp([$subcomponent], $props, 'class', 'collapse');
                    $this->mergeProp(
                        [$subcomponent],
                        $props,
                        'params',
                        array(
                            'data-collapse-target' => $mainblock_taget
                        )
                    );
                    $this->mergeJsmethodsProp([$subcomponent], $props, array('waypointsToggleCollapse'));
                }
                break;
        }

        parent::initWebPlatformModelProps($component, $props);
    }
}



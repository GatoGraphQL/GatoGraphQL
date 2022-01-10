<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Blog_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public const MODULE_MULTIPLE_AUTHOR_SIDEBAR = 'multiple-author-sidebar';
    public const MODULE_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR = 'multiple-authormaincontent-sidebar';
    public const MODULE_MULTIPLE_AUTHORCONTENT_SIDEBAR = 'multiple-authorcontent-sidebar';
    public const MODULE_MULTIPLE_AUTHORPOSTS_SIDEBAR = 'multiple-authorposts-sidebar';
    public const MODULE_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR = 'multiple-authorcategoryposts-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_AUTHOR_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHORCONTENT_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHORPOSTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
         // Add also the filter block for the Single Related Content, etc
            case self::MODULE_MULTIPLE_AUTHOR_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORCONTENT_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORPOSTS_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR:
                $vars = ApplicationState::getVars();
                $author = $vars['routing']['queried-object-id'];
                $filters = array(
                    self::MODULE_MULTIPLE_AUTHOR_SIDEBAR => null,
                    self::MODULE_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_AUTHORSECTIONINNER_MAINCONTENT_SIDEBAR],
                    self::MODULE_MULTIPLE_AUTHORCONTENT_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_AUTHORSECTIONINNER_CONTENT_SIDEBAR],
                    self::MODULE_MULTIPLE_AUTHORPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_AUTHORSECTIONINNER_POSTS_SIDEBAR],
                    self::MODULE_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR => [PoP_Module_Processor_SidebarMultipleInners::class, PoP_Module_Processor_SidebarMultipleInners::MODULE_MULTIPLE_AUTHORSECTIONINNER_CATEGORYPOSTS_SIDEBAR],
                );
                if ($filter = $filters[$module[1]] ?? null) {
                    $ret[] = $filter;
                }

                // Allow URE to add the Organization/Individual sidebars below
                $ret = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_UserCommunities_Module_Processor_SidebarMultiples:sidebar-layouts',
                    $ret,
                    $author,
                    $module
                );
                break;
        }

        return $ret;
    }

    public function getScreen(array $module)
    {
        $screens = array(
            self::MODULE_MULTIPLE_AUTHOR_SIDEBAR => POP_SCREEN_AUTHOR,
            self::MODULE_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR => POP_SCREEN_AUTHORSECTION,
            self::MODULE_MULTIPLE_AUTHORCONTENT_SIDEBAR => POP_SCREEN_AUTHORSECTION,
            self::MODULE_MULTIPLE_AUTHORPOSTS_SIDEBAR => POP_SCREEN_AUTHORSECTION,
            self::MODULE_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR => POP_SCREEN_AUTHORSECTION,
        );
        if ($screen = $screens[$module[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_AUTHOR_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORCONTENT_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORPOSTS_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORCATEGORYPOSTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($module);
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR:
                $submodules = array_diff(
                    $this->getSubmodules($module),
                    $this->getPermanentSubmodules($module)
                );
                foreach ($submodules as $submodule) {
                      // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
                    $mainblock_taget = '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-author > .blocksection-extensions > .pop-block.withfilter';

                    // Make the block be collapsible, open it when the main feed is reached, with waypoints
                    $this->appendProp([$submodule], $props, 'class', 'collapse');
                    $this->mergeProp(
                        [$submodule],
                        $props,
                        'params',
                        array(
                            'data-collapse-target' => $mainblock_taget
                        )
                    );
                    $this->mergeJsmethodsProp([$submodule], $props, array('waypointsToggleCollapse'));
                }
                break;
        }

        parent::initWebPlatformModelProps($module, $props);
    }
}



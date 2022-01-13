<?php
use PoP\ComponentModel\State\ApplicationState;

class PoPSP_URE_EM_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public const MODULE_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR = 'multiple-authorlocationposts-sidebar';
    public const MODULE_MULTIPLE_SECTION_LOCATIONPOSTS_SIDEBAR = 'multiple-section-locationposts-sidebar';
    public const MODULE_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR = 'multiple-tag-locationposts-sidebar';
    public const MODULE_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR = 'multiple-single-locationpost-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_LOCATIONPOSTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $filters = array(
                    self::MODULE_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR => [GD_Custom_EM_Module_Processor_CustomSectionSidebarInners::class, GD_Custom_EM_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_AUTHORLOCATIONPOSTS_SIDEBAR],
                );
                $ret[] = $filters[$module[1]];

                $ret = \PoP\Root\App::applyFilters(
                    'PoPSP_URE_EM_Module_Processor_SidebarMultiples:inner-modules:author',
                    $ret
                );
                break;

            default:
                $inners = array(
                    self::MODULE_MULTIPLE_SECTION_LOCATIONPOSTS_SIDEBAR => [GD_Custom_EM_Module_Processor_CustomSectionSidebarInners::class, GD_Custom_EM_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_LOCATIONPOSTS_SIDEBAR],
                    self::MODULE_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR => [GD_Custom_EM_Module_Processor_CustomSectionSidebarInners::class, GD_Custom_EM_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_TAGLOCATIONPOSTS_SIDEBAR],
                    self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR => [PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::class, PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR],
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
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR:
                return POP_SCREEN_AUTHORSECTION;

            case self::MODULE_MULTIPLE_SECTION_LOCATIONPOSTS_SIDEBAR:
                return POP_SCREEN_SECTION;

            case self::MODULE_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR:
                return POP_SCREEN_TAGSECTION;

            case self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR:
                return POP_SCREEN_SINGLE;
        }

        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;

            case self::MODULE_MULTIPLE_SECTION_LOCATIONPOSTS_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($module);
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR:
                $inners = array(
                    self::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR => [PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::class, PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR],
                );
                $submodule = $inners[$module[1]];

                // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
                $mainblock_taget = '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-singlepost > .blocksection-extensions > .pop-block > .blocksection-inners .content-single';

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
                break;
        }

        parent::initWebPlatformModelProps($module, $props);
    }
}



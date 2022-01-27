<?php

class PoPVP_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public const MODULE_MULTIPLE_SECTION_MYSTANCES_SIDEBAR = 'multiple-section-mystances-sidebar';
    public const MODULE_MULTIPLE_SECTION_STANCES_AUTHORROLE_SIDEBAR = 'multiple-section-stances-authorrole-sidebar';
    public const MODULE_MULTIPLE_SECTION_STANCES_GENERALSTANCE_SIDEBAR = 'multiple-section-stances-generalstance-sidebar';
    public const MODULE_MULTIPLE_SECTION_STANCES_SIDEBAR = 'multiple-section-stances-sidebar';
    public const MODULE_MULTIPLE_SECTION_STANCES_STANCE_SIDEBAR = 'multiple-section-stances-stance-sidebar';
    public const MODULE_MULTIPLE_TAG_STANCES_SIDEBAR = 'multiple-tag-stances-sidebar';
    public const MODULE_MULTIPLE_TAG_STANCES_STANCE_SIDEBAR = 'multiple-tag-stances-stance-sidebar';
    public const MODULE_MULTIPLE_AUTHOR_STANCES_SIDEBAR = 'multiple-author-stances-sidebar';
    public const MODULE_MULTIPLE_AUTHOR_STANCES_STANCE_SIDEBAR = 'multiple-author-stances-stance-sidebar';
    public const MODULE_MULTIPLE_SINGLE_STANCE_SIDEBAR = 'multiple-single-stance-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTION_STANCES_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_MYSTANCES_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_STANCES_AUTHORROLE_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_STANCES_STANCE_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_STANCES_GENERALSTANCE_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_TAG_STANCES_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_TAG_STANCES_STANCE_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHOR_STANCES_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHOR_STANCES_STANCE_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLE_STANCE_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inners = array(
            self::MODULE_MULTIPLE_SECTION_STANCES_SIDEBAR => [PoPVP_Module_Processor_CustomSectionSidebarInners::class, PoPVP_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_STANCES_SIDEBAR],
            self::MODULE_MULTIPLE_SECTION_MYSTANCES_SIDEBAR => [PoPVP_Module_Processor_CustomSectionSidebarInners::class, PoPVP_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_MYSTANCES_SIDEBAR],
            self::MODULE_MULTIPLE_SECTION_STANCES_AUTHORROLE_SIDEBAR => [PoPVP_Module_Processor_CustomSectionSidebarInners::class, PoPVP_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_STANCES_AUTHORROLE_SIDEBAR],
            self::MODULE_MULTIPLE_SECTION_STANCES_STANCE_SIDEBAR => [PoPVP_Module_Processor_CustomSectionSidebarInners::class, PoPVP_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_STANCES_STANCE_SIDEBAR],
            self::MODULE_MULTIPLE_SECTION_STANCES_GENERALSTANCE_SIDEBAR => [PoPVP_Module_Processor_CustomSectionSidebarInners::class, PoPVP_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_STANCES_GENERALSTANCE_SIDEBAR],
            self::MODULE_MULTIPLE_TAG_STANCES_SIDEBAR => [PoPVP_Module_Processor_CustomSectionSidebarInners::class, PoPVP_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_TAGSTANCES_SIDEBAR],
            self::MODULE_MULTIPLE_TAG_STANCES_STANCE_SIDEBAR => [PoPVP_Module_Processor_CustomSectionSidebarInners::class, PoPVP_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_TAGSTANCES_STANCE_SIDEBAR],
            self::MODULE_MULTIPLE_AUTHOR_STANCES_SIDEBAR => [PoPVP_Module_Processor_CustomSectionSidebarInners::class, PoPVP_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_AUTHORSTANCES_SIDEBAR],
            self::MODULE_MULTIPLE_AUTHOR_STANCES_STANCE_SIDEBAR => [PoPVP_Module_Processor_CustomSectionSidebarInners::class, PoPVP_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SECTIONINNER_AUTHORSTANCES_STANCE_SIDEBAR],
            self::MODULE_MULTIPLE_SINGLE_STANCE_SIDEBAR => [UserStance_Module_Processor_CustomSidebarDataloads::class, UserStance_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_STANCE_SIDEBAR],
        );
        if ($inner = $inners[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        switch ($module[1]) {
            case self::MODULE_MULTIPLE_AUTHOR_STANCES_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHOR_STANCES_STANCE_SIDEBAR:
                $ret = \PoP\Root\App::applyFilters(
                    'PoPVP_Module_Processor_SidebarMultiples:inner-modules:authorstances',
                    $ret
                );
                break;
        }

        return $ret;
    }

    public function getScreen(array $module)
    {
        $screens = array(
            self::MODULE_MULTIPLE_SECTION_STANCES_SIDEBAR => POP_SCREEN_SECTION,
            self::MODULE_MULTIPLE_SECTION_MYSTANCES_SIDEBAR => POP_SCREEN_SECTION,
            self::MODULE_MULTIPLE_SECTION_STANCES_AUTHORROLE_SIDEBAR => POP_SCREEN_SECTION,
            self::MODULE_MULTIPLE_SECTION_STANCES_STANCE_SIDEBAR => POP_SCREEN_SECTION,
            self::MODULE_MULTIPLE_SECTION_STANCES_GENERALSTANCE_SIDEBAR => POP_SCREEN_SECTION,
            self::MODULE_MULTIPLE_TAG_STANCES_SIDEBAR => POP_SCREEN_TAGSECTION,
            self::MODULE_MULTIPLE_TAG_STANCES_STANCE_SIDEBAR => POP_SCREEN_TAGSECTION,
            self::MODULE_MULTIPLE_AUTHOR_STANCES_SIDEBAR => POP_SCREEN_AUTHORSECTION,
            self::MODULE_MULTIPLE_AUTHOR_STANCES_STANCE_SIDEBAR => POP_SCREEN_AUTHORSECTION,
            self::MODULE_MULTIPLE_SINGLE_STANCE_SIDEBAR => POP_SCREEN_SINGLE,
        );
        if ($screen = $screens[$module[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTION_STANCES_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_STANCES_AUTHORROLE_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_STANCES_STANCE_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_STANCES_GENERALSTANCE_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_STANCES_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_STANCES_STANCE_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHOR_STANCES_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHOR_STANCES_STANCE_SIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_STANCE_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;

            case self::MODULE_MULTIPLE_SECTION_MYSTANCES_SIDEBAR:
                return POP_SCREENGROUP_CONTENTWRITE;
        }

        return parent::getScreengroup($module);
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SINGLE_STANCE_SIDEBAR:
                $inners = array(
                    self::MODULE_MULTIPLE_SINGLE_STANCE_SIDEBAR => [UserStance_Module_Processor_CustomSidebarDataloads::class, UserStance_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_STANCE_SIDEBAR],
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



<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

class GD_EM_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public const MODULE_MULTIPLE_SECTION_EVENTS_CALENDAR_SIDEBAR = 'multiple-events-calendar-sidebar';
    public const MODULE_MULTIPLE_SECTION_EVENTS_SIDEBAR = 'multiple-section-events-sidebar';
    public const MODULE_MULTIPLE_SECTION_PASTEVENTS_SIDEBAR = 'multiple-section-pastevents-sidebar';
    public const MODULE_MULTIPLE_TAG_EVENTS_CALENDAR_SIDEBAR = 'multiple-tag-events-calendar-sidebar';
    public const MODULE_MULTIPLE_TAG_EVENTS_SIDEBAR = 'multiple-tag-events-sidebar';
    public const MODULE_MULTIPLE_TAG_PASTEVENTS_SIDEBAR = 'multiple-tag-pastevents-sidebar';
    public const MODULE_MULTIPLE_SINGLE_EVENT_SIDEBAR = 'multiple-single-event-sidebar';
    public const MODULE_MULTIPLE_SINGLE_PASTEVENT_SIDEBAR = 'multiple-single-pastevent-sidebar';
    public const MODULE_MULTIPLE_AUTHOREVENTS_SIDEBAR = 'multiple-authorevents-sidebar';
    public const MODULE_MULTIPLE_AUTHORPASTEVENTS_SIDEBAR = 'multiple-authorpastevents-sidebar';
    public const MODULE_MULTIPLE_AUTHOREVENTSCALENDAR_SIDEBAR = 'multiple-authoreventscalendar-sidebar';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_SECTION_EVENTS_CALENDAR_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_EVENTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SECTION_PASTEVENTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_TAG_EVENTS_CALENDAR_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_TAG_EVENTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_TAG_PASTEVENTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLE_EVENT_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_SINGLE_PASTEVENT_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHOREVENTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHORPASTEVENTS_SIDEBAR],
            [self::class, self::MODULE_MULTIPLE_AUTHOREVENTSCALENDAR_SIDEBAR],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $blocks = array(
            self::MODULE_MULTIPLE_SECTION_EVENTS_CALENDAR_SIDEBAR => [GD_EM_Module_Processor_CustomSectionSidebarInners::class, GD_EM_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SIDEBARINNER_SECTION_EVENTS_CALENDAR],
            self::MODULE_MULTIPLE_SECTION_EVENTS_SIDEBAR => [GD_EM_Module_Processor_CustomSectionSidebarInners::class, GD_EM_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SIDEBARINNER_SECTION_EVENTS],
            self::MODULE_MULTIPLE_SECTION_PASTEVENTS_SIDEBAR => [GD_EM_Module_Processor_CustomSectionSidebarInners::class, GD_EM_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SIDEBARINNER_SECTION_PASTEVENTS],
            self::MODULE_MULTIPLE_TAG_EVENTS_CALENDAR_SIDEBAR => [GD_EM_Module_Processor_CustomSectionSidebarInners::class, GD_EM_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SIDEBARINNER_TAG_EVENTS_CALENDAR],
            self::MODULE_MULTIPLE_TAG_EVENTS_SIDEBAR => [GD_EM_Module_Processor_CustomSectionSidebarInners::class, GD_EM_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SIDEBARINNER_TAG_EVENTS],
            self::MODULE_MULTIPLE_TAG_PASTEVENTS_SIDEBAR => [GD_EM_Module_Processor_CustomSectionSidebarInners::class, GD_EM_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SIDEBARINNER_TAG_PASTEVENTS],
            self::MODULE_MULTIPLE_SINGLE_EVENT_SIDEBAR => [PoP_Events_Module_Processor_CustomSidebarDataloads::class, PoP_Events_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_EVENT_SIDEBAR],
            self::MODULE_MULTIPLE_SINGLE_PASTEVENT_SIDEBAR => [PoP_Events_Module_Processor_CustomSidebarDataloads::class, PoP_Events_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_PASTEVENT_SIDEBAR],
        );
        if ($block = $blocks[$module[1]] ?? null) {
            $ret[] = $block;
        } else {
            switch ($module[1]) {
                case self::MODULE_MULTIPLE_AUTHOREVENTS_SIDEBAR:
                case self::MODULE_MULTIPLE_AUTHORPASTEVENTS_SIDEBAR:
                case self::MODULE_MULTIPLE_AUTHOREVENTSCALENDAR_SIDEBAR:
                    $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                    $filters = array(
                        self::MODULE_MULTIPLE_AUTHOREVENTS_SIDEBAR => [GD_EM_Module_Processor_CustomSectionSidebarInners::class, GD_EM_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SIDEBARINNER_SECTION_AUTHOREVENTS],
                        self::MODULE_MULTIPLE_AUTHORPASTEVENTS_SIDEBAR => [GD_EM_Module_Processor_CustomSectionSidebarInners::class, GD_EM_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SIDEBARINNER_SECTION_AUTHORPASTEVENTS],
                        self::MODULE_MULTIPLE_AUTHOREVENTSCALENDAR_SIDEBAR => [GD_EM_Module_Processor_CustomSectionSidebarInners::class, GD_EM_Module_Processor_CustomSectionSidebarInners::MODULE_MULTIPLE_SIDEBARINNER_SECTION_AUTHOREVENTSCALENDAR],
                    );
                    $ret[] = $filters[$module[1]];

                    // Allow User Role Editor to add blocks specific to that user role
                    $ret = HooksAPIFacade::getInstance()->applyFilters(
                        'PoP_EM_Module_Processor_SidebarMultiples:inner-modules',
                        $ret,
                        $author
                    );
                    break;
            }
        }

        return $ret;
    }

    public function getScreen(array $module)
    {
        $screens = array(
            self::MODULE_MULTIPLE_SECTION_EVENTS_CALENDAR_SIDEBAR => POP_SCREEN_SECTIONCALENDAR,
            self::MODULE_MULTIPLE_SECTION_EVENTS_SIDEBAR => POP_SCREEN_SECTION,
            self::MODULE_MULTIPLE_SECTION_PASTEVENTS_SIDEBAR => POP_SCREEN_SECTION,
            self::MODULE_MULTIPLE_TAG_EVENTS_CALENDAR_SIDEBAR => POP_SCREEN_TAGSECTIONCALENDAR,
            self::MODULE_MULTIPLE_TAG_EVENTS_SIDEBAR => POP_SCREEN_TAGSECTION,
            self::MODULE_MULTIPLE_TAG_PASTEVENTS_SIDEBAR => POP_SCREEN_TAGSECTION,
            self::MODULE_MULTIPLE_SINGLE_EVENT_SIDEBAR => POP_SCREEN_SINGLE,
            self::MODULE_MULTIPLE_SINGLE_PASTEVENT_SIDEBAR => POP_SCREEN_SINGLE,
            self::MODULE_MULTIPLE_AUTHOREVENTS_SIDEBAR => POP_SCREEN_AUTHORSECTION,
            self::MODULE_MULTIPLE_AUTHORPASTEVENTS_SIDEBAR => POP_SCREEN_AUTHORSECTION,
            self::MODULE_MULTIPLE_AUTHOREVENTSCALENDAR_SIDEBAR => POP_SCREEN_AUTHORSECTIONCALENDAR,
        );
        if ($screen = $screens[$module[1]] ?? null) {
            return $screen;
        }

        return parent::getScreen($module);
    }

    public function getScreengroup(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SECTION_EVENTS_CALENDAR_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_EVENTS_SIDEBAR:
            case self::MODULE_MULTIPLE_SECTION_PASTEVENTS_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_EVENTS_CALENDAR_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_EVENTS_SIDEBAR:
            case self::MODULE_MULTIPLE_TAG_PASTEVENTS_SIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_EVENT_SIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_PASTEVENT_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHOREVENTS_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHORPASTEVENTS_SIDEBAR:
            case self::MODULE_MULTIPLE_AUTHOREVENTSCALENDAR_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($module);
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_MULTIPLE_SINGLE_EVENT_SIDEBAR:
            case self::MODULE_MULTIPLE_SINGLE_PASTEVENT_SIDEBAR:
                $inners = array(
                    self::MODULE_MULTIPLE_SINGLE_EVENT_SIDEBAR => [PoP_Events_Module_Processor_CustomSidebarDataloads::class, PoP_Events_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_EVENT_SIDEBAR],
                    self::MODULE_MULTIPLE_SINGLE_PASTEVENT_SIDEBAR => [PoP_Events_Module_Processor_CustomSidebarDataloads::class, PoP_Events_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_SINGLE_PASTEVENT_SIDEBAR],
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



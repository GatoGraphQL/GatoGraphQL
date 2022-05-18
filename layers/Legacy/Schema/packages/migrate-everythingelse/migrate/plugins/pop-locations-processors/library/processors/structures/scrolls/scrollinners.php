<?php

class PoP_Locations_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const COMPONENT_SCROLLINNER_USERS_MAP = 'scrollinner-users-map';
    public final const COMPONENT_SCROLLINNER_USER_MAP = 'scrollinner-user-map';
    public final const COMPONENT_SCROLLINNER_USERS_HORIZONTALMAP = 'scrollinner-users-horizontalmap';
    public final const COMPONENT_SCROLLINNER_LOCATIONS = 'scrollinner-locations';
    public final const COMPONENT_SCROLLINNER_LOCATIONS_MAP = 'scrollinner-locations-map';
    public final const COMPONENT_SCROLLINNER_EVENTS_MAP = 'scrollinner-events-map';
    public final const COMPONENT_SCROLLINNER_PASTEVENTS_MAP = 'scrollinner-pastevents-map';
    public final const COMPONENT_SCROLLINNER_EVENTS_HORIZONTALMAP = 'scrollinner-events-horizontalmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLINNER_USERS_MAP],
            [self::class, self::COMPONENT_SCROLLINNER_USER_MAP],
            [self::class, self::COMPONENT_SCROLLINNER_USERS_HORIZONTALMAP],
            [self::class, self::COMPONENT_SCROLLINNER_LOCATIONS],
            [self::class, self::COMPONENT_SCROLLINNER_LOCATIONS_MAP],
            [self::class, self::COMPONENT_SCROLLINNER_EVENTS_MAP],
            [self::class, self::COMPONENT_SCROLLINNER_PASTEVENTS_MAP],
            [self::class, self::COMPONENT_SCROLLINNER_EVENTS_HORIZONTALMAP],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLINNER_USERS_MAP:
            case self::COMPONENT_SCROLLINNER_USER_MAP:
            case self::COMPONENT_SCROLLINNER_USERS_HORIZONTALMAP:
            case self::COMPONENT_SCROLLINNER_LOCATIONS_MAP:
            case self::COMPONENT_SCROLLINNER_EVENTS_MAP:
            case self::COMPONENT_SCROLLINNER_PASTEVENTS_MAP:
            case self::COMPONENT_SCROLLINNER_EVENTS_HORIZONTALMAP:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::COMPONENT_SCROLLINNER_USERS_MAP => [GD_EM_Module_Processor_MultipleUserLayouts::class, GD_EM_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_MAPDETAILS],
            self::COMPONENT_SCROLLINNER_USER_MAP => [GD_EM_Module_Processor_CustomPreviewUserLayouts::class, GD_EM_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_MAPDETAILS],
            self::COMPONENT_SCROLLINNER_USERS_HORIZONTALMAP => [GD_EM_Module_Processor_CustomPreviewUserLayouts::class, GD_EM_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS],//[self::class, self::COMPONENT_LAYOUT_MULTIPLEUSER_HORIZONTALMAPDETAILS],

            self::COMPONENT_SCROLLINNER_LOCATIONS => [GD_EM_Module_Processor_LocationLayouts::class, GD_EM_Module_Processor_LocationLayouts::COMPONENT_EM_LAYOUT_LOCATIONS],

            self::COMPONENT_SCROLLINNER_LOCATIONS_MAP => [PoP_Module_Processor_MapMarkerScripts::class, PoP_Module_Processor_MapMarkerScripts::COMPONENT_MAP_SCRIPT_MARKERS],
            self::COMPONENT_SCROLLINNER_EVENTS_MAP => [PoP_Events_Locations_Module_Processor_CustomPreviewPostLayouts::class, PoP_Events_Locations_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_MAPDETAILS],
            self::COMPONENT_SCROLLINNER_PASTEVENTS_MAP => [PoP_Events_Locations_Module_Processor_CustomPreviewPostLayouts::class, PoP_Events_Locations_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_MAPDETAILS],
            self::COMPONENT_SCROLLINNER_EVENTS_HORIZONTALMAP => [PoP_Events_Locations_Module_Processor_CustomPreviewPostLayouts::class, PoP_Events_Locations_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_HORIZONTALMAPDETAILS],
        );
        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



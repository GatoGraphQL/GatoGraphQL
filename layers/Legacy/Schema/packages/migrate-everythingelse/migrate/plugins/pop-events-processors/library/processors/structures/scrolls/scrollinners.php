<?php

class PoP_Events_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const COMPONENT_SCROLLINNER_EVENTS_NAVIGATOR = 'scrollinner-events-navigator';
    public final const COMPONENT_SCROLLINNER_PASTEVENTS_NAVIGATOR = 'scrollinner-pastevents-navigator';
    public final const COMPONENT_SCROLLINNER_EVENTS_ADDONS = 'scrollinner-events-addons';
    public final const COMPONENT_SCROLLINNER_PASTEVENTS_ADDONS = 'scrollinner-pastevents-addons';
    public final const COMPONENT_SCROLLINNER_EVENTS_DETAILS = 'scrollinner-events-details';
    public final const COMPONENT_SCROLLINNER_PASTEVENTS_DETAILS = 'scrollinner-pastevents-details';
    public final const COMPONENT_SCROLLINNER_EVENTS_SIMPLEVIEW = 'scrollinner-events-simpleview';
    public final const COMPONENT_SCROLLINNER_PASTEVENTS_SIMPLEVIEW = 'scrollinner-pastevents-simpleview';
    public final const COMPONENT_SCROLLINNER_EVENTS_FULLVIEW = 'scrollinner-events-fullview';
    public final const COMPONENT_SCROLLINNER_PASTEVENTS_FULLVIEW = 'scrollinner-pastevents-fullview';
    public final const COMPONENT_SCROLLINNER_AUTHOREVENTS_FULLVIEW = 'scrollinner-authorevents-fullview';
    public final const COMPONENT_SCROLLINNER_AUTHORPASTEVENTS_FULLVIEW = 'scrollinner-authorpastevents-fullview';
    public final const COMPONENT_SCROLLINNER_EVENTS_THUMBNAIL = 'scrollinner-events-thumbnail';
    public final const COMPONENT_SCROLLINNER_PASTEVENTS_THUMBNAIL = 'scrollinner-pastevents-thumbnail';
    public final const COMPONENT_SCROLLINNER_EVENTS_LIST = 'scrollinner-events-list';
    public final const COMPONENT_SCROLLINNER_PASTEVENTS_LIST = 'scrollinner-pastevents-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLINNER_EVENTS_NAVIGATOR],
            [self::class, self::COMPONENT_SCROLLINNER_PASTEVENTS_NAVIGATOR],
            [self::class, self::COMPONENT_SCROLLINNER_EVENTS_ADDONS],
            [self::class, self::COMPONENT_SCROLLINNER_PASTEVENTS_ADDONS],
            [self::class, self::COMPONENT_SCROLLINNER_EVENTS_DETAILS],
            [self::class, self::COMPONENT_SCROLLINNER_PASTEVENTS_DETAILS],
            [self::class, self::COMPONENT_SCROLLINNER_EVENTS_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_PASTEVENTS_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_EVENTS_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_PASTEVENTS_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_EVENTS_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLLINNER_PASTEVENTS_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLLINNER_EVENTS_LIST],
            [self::class, self::COMPONENT_SCROLLINNER_PASTEVENTS_LIST],
            [self::class, self::COMPONENT_SCROLLINNER_AUTHOREVENTS_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_AUTHORPASTEVENTS_FULLVIEW],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLINNER_EVENTS_THUMBNAIL:
            case self::COMPONENT_SCROLLINNER_PASTEVENTS_THUMBNAIL:
                // Allow ThemeStyle Expansive to override the grid
                return \PoP\Root\App::applyFilters(
                    POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
                    array(
                        'row-items' => 2,
                        'class' => 'col-xsm-6'
                    )
                );

            case self::COMPONENT_SCROLLINNER_EVENTS_NAVIGATOR:
            case self::COMPONENT_SCROLLINNER_PASTEVENTS_NAVIGATOR:
            case self::COMPONENT_SCROLLINNER_EVENTS_ADDONS:
            case self::COMPONENT_SCROLLINNER_PASTEVENTS_ADDONS:
            case self::COMPONENT_SCROLLINNER_EVENTS_DETAILS:
            case self::COMPONENT_SCROLLINNER_PASTEVENTS_DETAILS:
            case self::COMPONENT_SCROLLINNER_EVENTS_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_PASTEVENTS_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_EVENTS_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_PASTEVENTS_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_EVENTS_LIST:
            case self::COMPONENT_SCROLLINNER_PASTEVENTS_LIST:
            case self::COMPONENT_SCROLLINNER_AUTHOREVENTS_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_AUTHORPASTEVENTS_FULLVIEW:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_SCROLLINNER_PASTEVENTS_NAVIGATOR => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR],
            self::COMPONENT_SCROLLINNER_EVENTS_NAVIGATOR => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR],

            self::COMPONENT_SCROLLINNER_PASTEVENTS_ADDONS => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS],
            self::COMPONENT_SCROLLINNER_EVENTS_ADDONS => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_ADDONS],

            self::COMPONENT_SCROLLINNER_PASTEVENTS_DETAILS => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS],
            self::COMPONENT_SCROLLINNER_EVENTS_DETAILS => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS],

            self::COMPONENT_SCROLLINNER_PASTEVENTS_THUMBNAIL => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL],
            self::COMPONENT_SCROLLINNER_EVENTS_THUMBNAIL => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL],

            self::COMPONENT_SCROLLINNER_PASTEVENTS_LIST => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_LIST],
            self::COMPONENT_SCROLLINNER_EVENTS_LIST => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_LIST],

            self::COMPONENT_SCROLLINNER_EVENTS_SIMPLEVIEW => [GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW],
            self::COMPONENT_SCROLLINNER_PASTEVENTS_SIMPLEVIEW => [GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW],

            self::COMPONENT_SCROLLINNER_EVENTS_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_EVENT],
            self::COMPONENT_SCROLLINNER_PASTEVENTS_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_PASTEVENT],

            self::COMPONENT_SCROLLINNER_AUTHOREVENTS_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_EVENT],
            self::COMPONENT_SCROLLINNER_AUTHORPASTEVENTS_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_PASTEVENT],
        );
        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



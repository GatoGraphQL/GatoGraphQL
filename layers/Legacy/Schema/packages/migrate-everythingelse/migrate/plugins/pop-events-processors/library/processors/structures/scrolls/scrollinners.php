<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Events_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public const MODULE_SCROLLINNER_EVENTS_NAVIGATOR = 'scrollinner-events-navigator';
    public const MODULE_SCROLLINNER_PASTEVENTS_NAVIGATOR = 'scrollinner-pastevents-navigator';
    public const MODULE_SCROLLINNER_EVENTS_ADDONS = 'scrollinner-events-addons';
    public const MODULE_SCROLLINNER_PASTEVENTS_ADDONS = 'scrollinner-pastevents-addons';
    public const MODULE_SCROLLINNER_EVENTS_DETAILS = 'scrollinner-events-details';
    public const MODULE_SCROLLINNER_PASTEVENTS_DETAILS = 'scrollinner-pastevents-details';
    public const MODULE_SCROLLINNER_EVENTS_SIMPLEVIEW = 'scrollinner-events-simpleview';
    public const MODULE_SCROLLINNER_PASTEVENTS_SIMPLEVIEW = 'scrollinner-pastevents-simpleview';
    public const MODULE_SCROLLINNER_EVENTS_FULLVIEW = 'scrollinner-events-fullview';
    public const MODULE_SCROLLINNER_PASTEVENTS_FULLVIEW = 'scrollinner-pastevents-fullview';
    public const MODULE_SCROLLINNER_AUTHOREVENTS_FULLVIEW = 'scrollinner-authorevents-fullview';
    public const MODULE_SCROLLINNER_AUTHORPASTEVENTS_FULLVIEW = 'scrollinner-authorpastevents-fullview';
    public const MODULE_SCROLLINNER_EVENTS_THUMBNAIL = 'scrollinner-events-thumbnail';
    public const MODULE_SCROLLINNER_PASTEVENTS_THUMBNAIL = 'scrollinner-pastevents-thumbnail';
    public const MODULE_SCROLLINNER_EVENTS_LIST = 'scrollinner-events-list';
    public const MODULE_SCROLLINNER_PASTEVENTS_LIST = 'scrollinner-pastevents-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_EVENTS_NAVIGATOR],
            [self::class, self::MODULE_SCROLLINNER_PASTEVENTS_NAVIGATOR],
            [self::class, self::MODULE_SCROLLINNER_EVENTS_ADDONS],
            [self::class, self::MODULE_SCROLLINNER_PASTEVENTS_ADDONS],
            [self::class, self::MODULE_SCROLLINNER_EVENTS_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_PASTEVENTS_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_EVENTS_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_PASTEVENTS_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_EVENTS_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_PASTEVENTS_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_EVENTS_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_PASTEVENTS_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_EVENTS_LIST],
            [self::class, self::MODULE_SCROLLINNER_PASTEVENTS_LIST],
            [self::class, self::MODULE_SCROLLINNER_AUTHOREVENTS_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_AUTHORPASTEVENTS_FULLVIEW],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_EVENTS_THUMBNAIL:
            case self::MODULE_SCROLLINNER_PASTEVENTS_THUMBNAIL:
                // Allow ThemeStyle Expansive to override the grid
                return HooksAPIFacade::getInstance()->applyFilters(
                    POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
                    array(
                        'row-items' => 2,
                        'class' => 'col-xsm-6'
                    )
                );

            case self::MODULE_SCROLLINNER_EVENTS_NAVIGATOR:
            case self::MODULE_SCROLLINNER_PASTEVENTS_NAVIGATOR:
            case self::MODULE_SCROLLINNER_EVENTS_ADDONS:
            case self::MODULE_SCROLLINNER_PASTEVENTS_ADDONS:
            case self::MODULE_SCROLLINNER_EVENTS_DETAILS:
            case self::MODULE_SCROLLINNER_PASTEVENTS_DETAILS:
            case self::MODULE_SCROLLINNER_EVENTS_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_PASTEVENTS_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_EVENTS_FULLVIEW:
            case self::MODULE_SCROLLINNER_PASTEVENTS_FULLVIEW:
            case self::MODULE_SCROLLINNER_EVENTS_LIST:
            case self::MODULE_SCROLLINNER_PASTEVENTS_LIST:
            case self::MODULE_SCROLLINNER_AUTHOREVENTS_FULLVIEW:
            case self::MODULE_SCROLLINNER_AUTHORPASTEVENTS_FULLVIEW:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($module, $props);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_SCROLLINNER_PASTEVENTS_NAVIGATOR => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR],
            self::MODULE_SCROLLINNER_EVENTS_NAVIGATOR => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR],

            self::MODULE_SCROLLINNER_PASTEVENTS_ADDONS => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS],
            self::MODULE_SCROLLINNER_EVENTS_ADDONS => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_ADDONS],

            self::MODULE_SCROLLINNER_PASTEVENTS_DETAILS => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS],
            self::MODULE_SCROLLINNER_EVENTS_DETAILS => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_DETAILS],

            self::MODULE_SCROLLINNER_PASTEVENTS_THUMBNAIL => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL],
            self::MODULE_SCROLLINNER_EVENTS_THUMBNAIL => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL],

            self::MODULE_SCROLLINNER_PASTEVENTS_LIST => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_LIST],
            self::MODULE_SCROLLINNER_EVENTS_LIST => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_LIST],

            self::MODULE_SCROLLINNER_EVENTS_SIMPLEVIEW => [GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW],
            self::MODULE_SCROLLINNER_PASTEVENTS_SIMPLEVIEW => [GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW],

            self::MODULE_SCROLLINNER_EVENTS_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_EVENT],
            self::MODULE_SCROLLINNER_PASTEVENTS_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_PASTEVENT],

            self::MODULE_SCROLLINNER_AUTHOREVENTS_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_EVENT],
            self::MODULE_SCROLLINNER_AUTHORPASTEVENTS_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_PASTEVENT],
        );
        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



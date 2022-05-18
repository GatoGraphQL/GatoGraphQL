<?php

class PoPTheme_Wassup_EM_AE_Module_Processor_ScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS = 'scrollinner-automatedemails-events-details';
    public final const MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW = 'scrollinner-automatedemails-events-simpleview';
    public final const MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW = 'scrollinner-automatedemails-events-fullview';
    public final const MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL = 'scrollinner-automatedemails-events-thumbnail';
    public final const MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST = 'scrollinner-automatedemails-events-list';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL:

                // Allow ThemeStyle Expansive to override the grid
                return \PoP\Root\App::applyFilters(
                    POP_HOOK_SCROLLINNER_AUTOMATEDEMAILS_THUMBNAIL_GRID,
                    array(
                        'row-items' => 2,
                        'class' => 'col-xsm-6'
                    )
                );

            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST:

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
            self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS => [PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS],
            self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL => [PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL],
            self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST => [PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST],
            self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_SimpleViewPreviewPostLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_SimpleViewPreviewPostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW],
            self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_FullViewLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_FullViewLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW:
                // Add an extra space at the bottom of each post
                $this->appendProp($module, $props, 'class', 'email-scrollelem-post');
        }

        parent::initModelProps($module, $props);
    }
}



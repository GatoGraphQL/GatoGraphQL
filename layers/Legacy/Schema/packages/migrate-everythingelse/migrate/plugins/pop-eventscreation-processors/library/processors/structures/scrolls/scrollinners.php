<?php

class PoP_EventsCreation_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public const MODULE_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW = 'scrollinner-myevents-simpleviewpreview';
    public const MODULE_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW = 'scrollinner-mypastevents-simpleviewpreview';
    public const MODULE_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW = 'scrollinner-myevents-fullviewpreview';
    public const MODULE_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW = 'scrollinner-mypastevents-fullviewpreview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW],
            [self::class, self::MODULE_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW:
            case self::MODULE_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW:
            case self::MODULE_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW:
            case self::MODULE_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW:
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
            self::MODULE_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW => [GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW],
            self::MODULE_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW => [GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW],

            self::MODULE_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_EVENT],
            self::MODULE_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_PASTEVENT],
        );
        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



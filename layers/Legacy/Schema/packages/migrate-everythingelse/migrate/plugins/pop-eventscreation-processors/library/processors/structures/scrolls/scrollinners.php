<?php

class PoP_EventsCreation_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW = 'scrollinner-myevents-simpleviewpreview';
    public final const MODULE_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW = 'scrollinner-mypastevents-simpleviewpreview';
    public final const MODULE_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW = 'scrollinner-myevents-fullviewpreview';
    public final const MODULE_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW = 'scrollinner-mypastevents-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW],
            [self::class, self::MODULE_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW],
        );
    }

    public function getLayoutGrid(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW:
            case self::MODULE_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW:
            case self::MODULE_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW:
            case self::MODULE_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($componentVariation, $props);
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        $layouts = array(
            self::MODULE_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW => [GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW],
            self::MODULE_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW => [GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW],

            self::MODULE_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_EVENT],
            self::MODULE_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_PASTEVENT],
        );
        if ($layout = $layouts[$componentVariation[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



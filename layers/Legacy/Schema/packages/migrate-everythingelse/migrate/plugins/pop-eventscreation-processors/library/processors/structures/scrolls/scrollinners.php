<?php

class PoP_EventsCreation_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW = 'scrollinner-myevents-simpleviewpreview';
    public final const MODULE_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW = 'scrollinner-mypastevents-simpleviewpreview';
    public final const MODULE_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW = 'scrollinner-myevents-fullviewpreview';
    public final const MODULE_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW = 'scrollinner-mypastevents-fullviewpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW:
            case self::COMPONENT_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW:
            case self::COMPONENT_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW:
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
            self::COMPONENT_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW => [GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW],
            self::COMPONENT_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW => [GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW],

            self::COMPONENT_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_EVENT],
            self::COMPONENT_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_PASTEVENT],
        );
        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



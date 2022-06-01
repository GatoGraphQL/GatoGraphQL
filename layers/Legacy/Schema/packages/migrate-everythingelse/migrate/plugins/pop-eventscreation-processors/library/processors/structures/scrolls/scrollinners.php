<?php

class PoP_EventsCreation_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const COMPONENT_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW = 'scrollinner-myevents-simpleviewpreview';
    public final const COMPONENT_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW = 'scrollinner-mypastevents-simpleviewpreview';
    public final const COMPONENT_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW = 'scrollinner-myevents-fullviewpreview';
    public final const COMPONENT_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW = 'scrollinner-mypastevents-fullviewpreview';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW,
            self::COMPONENT_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW,
            self::COMPONENT_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW,
            self::COMPONENT_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW,
        );
    }

    public function getLayoutGrid(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
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

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_SCROLLINNER_MYEVENTS_SIMPLEVIEWPREVIEW => [GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW],
            self::COMPONENT_SCROLLINNER_MYPASTEVENTS_SIMPLEVIEWPREVIEW => [GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW],

            self::COMPONENT_SCROLLINNER_MYEVENTS_FULLVIEWPREVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_EVENT],
            self::COMPONENT_SCROLLINNER_MYPASTEVENTS_FULLVIEWPREVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_PASTEVENT],
        );
        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



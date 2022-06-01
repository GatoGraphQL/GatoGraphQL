<?php

class GD_EM_Module_Processor_TableInners extends PoP_Module_Processor_TableInnersBase
{
    public final const COMPONENT_TABLEINNER_MYEVENTS = 'tableinner-myevents';
    public final const COMPONENT_TABLEINNER_MYPASTEVENTS = 'tableinner-mypastevents';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TABLEINNER_MYEVENTS,
            self::COMPONENT_TABLEINNER_MYPASTEVENTS,
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        // Main layout
        switch ($component->name) {
            case self::COMPONENT_TABLEINNER_MYEVENTS:
                $ret[] = [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_EDIT];
                $ret[] = [PoP_Module_Processor_EventDateAndTimeLayouts::class, PoP_Module_Processor_EventDateAndTimeLayouts::COMPONENT_EM_LAYOUTEVENT_TABLECOL];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::COMPONENT_LAYOUTPOST_STATUS];
                break;

            case self::COMPONENT_TABLEINNER_MYPASTEVENTS:
                $ret[] = [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT];
                $ret[] = [PoP_Module_Processor_EventDateAndTimeLayouts::class, PoP_Module_Processor_EventDateAndTimeLayouts::COMPONENT_EM_LAYOUTEVENT_TABLECOL];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::COMPONENT_LAYOUTPOST_STATUS];
                break;
        }

        return $ret;
    }
}



<?php

class GD_EM_Module_Processor_TableInners extends PoP_Module_Processor_TableInnersBase
{
    public final const MODULE_TABLEINNER_MYEVENTS = 'tableinner-myevents';
    public final const MODULE_TABLEINNER_MYPASTEVENTS = 'tableinner-mypastevents';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABLEINNER_MYEVENTS],
            [self::class, self::COMPONENT_TABLEINNER_MYPASTEVENTS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        // Main layout
        switch ($component[1]) {
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



<?php

class GD_EM_Module_Processor_TableInners extends PoP_Module_Processor_TableInnersBase
{
    public const MODULE_TABLEINNER_MYEVENTS = 'tableinner-myevents';
    public const MODULE_TABLEINNER_MYPASTEVENTS = 'tableinner-mypastevents';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLEINNER_MYEVENTS],
            [self::class, self::MODULE_TABLEINNER_MYPASTEVENTS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        // Main layout
        switch ($module[1]) {
            case self::MODULE_TABLEINNER_MYEVENTS:
                $ret[] = [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_EDIT];
                $ret[] = [PoP_Module_Processor_EventDateAndTimeLayouts::class, PoP_Module_Processor_EventDateAndTimeLayouts::MODULE_EM_LAYOUTEVENT_TABLECOL];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::MODULE_LAYOUTPOST_STATUS];
                break;

            case self::MODULE_TABLEINNER_MYPASTEVENTS:
                $ret[] = [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT];
                $ret[] = [PoP_Module_Processor_EventDateAndTimeLayouts::class, PoP_Module_Processor_EventDateAndTimeLayouts::MODULE_EM_LAYOUTEVENT_TABLECOL];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::MODULE_LAYOUTPOST_STATUS];
                break;
        }

        return $ret;
    }
}



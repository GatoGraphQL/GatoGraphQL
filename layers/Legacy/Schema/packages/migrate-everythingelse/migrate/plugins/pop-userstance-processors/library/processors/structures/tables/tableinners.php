<?php

class UserStance_Module_Processor_TableInners extends PoP_Module_Processor_TableInnersBase
{
    public final const MODULE_TABLEINNER_MYSTANCES = 'tableinner-mystances';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLEINNER_MYSTANCES],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        // Main layout
        switch ($module[1]) {
            case self::MODULE_TABLEINNER_MYSTANCES:
                $ret[] = [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_EDIT];
                $ret[] = [PoP_Module_Processor_PostDateLayouts::class, PoP_Module_Processor_PostDateLayouts::MODULE_LAYOUTPOST_DATE];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::MODULE_LAYOUTPOST_STATUS];
                break;
        }

        return $ret;
    }
}



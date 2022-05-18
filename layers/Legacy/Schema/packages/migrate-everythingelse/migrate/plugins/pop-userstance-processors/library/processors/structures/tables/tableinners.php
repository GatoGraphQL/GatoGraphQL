<?php

class UserStance_Module_Processor_TableInners extends PoP_Module_Processor_TableInnersBase
{
    public final const COMPONENT_TABLEINNER_MYSTANCES = 'tableinner-mystances';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABLEINNER_MYSTANCES],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        // Main layout
        switch ($component[1]) {
            case self::COMPONENT_TABLEINNER_MYSTANCES:
                $ret[] = [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_EDIT];
                $ret[] = [PoP_Module_Processor_PostDateLayouts::class, PoP_Module_Processor_PostDateLayouts::COMPONENT_LAYOUTPOST_DATE];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::COMPONENT_LAYOUTPOST_STATUS];
                break;
        }

        return $ret;
    }
}



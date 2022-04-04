<?php

class GD_Custom_EM_Module_Processor_TableInners extends PoP_Module_Processor_TableInnersBase
{
    public final const MODULE_TABLEINNER_MYLOCATIONPOSTS = 'tableinner-mylocationposts';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLEINNER_MYLOCATIONPOSTS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        // Main layout
        switch ($module[1]) {
            case self::MODULE_TABLEINNER_MYLOCATIONPOSTS:
                $ret[] = [PoP_LocationPostsCreation_Module_Processor_CustomPreviewPostLayouts::class, PoP_LocationPostsCreation_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT];
                $ret[] = [PoP_Module_Processor_PostDateLayouts::class, PoP_Module_Processor_PostDateLayouts::MODULE_LAYOUTPOST_DATE];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::MODULE_LAYOUTPOST_STATUS];
                break;
        }

        return $ret;
    }
}



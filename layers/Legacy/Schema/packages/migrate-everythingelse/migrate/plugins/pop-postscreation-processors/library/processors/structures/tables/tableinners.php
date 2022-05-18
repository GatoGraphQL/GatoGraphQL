<?php

class PoP_ContentPostLinksCreation_Module_Processor_TableInners extends PoP_Module_Processor_TableInnersBase
{
    public final const MODULE_TABLEINNER_MYLINKS = 'tableinner-mylinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLEINNER_MYLINKS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        // Main layout
        switch ($component[1]) {
            case self::MODULE_TABLEINNER_MYLINKS:
                $ret[] = [PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT];
                $ret[] = [PoP_Module_Processor_PostDateLayouts::class, PoP_Module_Processor_PostDateLayouts::MODULE_LAYOUTPOST_DATE];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::MODULE_LAYOUTPOST_STATUS];
                break;
        }

        return $ret;
    }
}



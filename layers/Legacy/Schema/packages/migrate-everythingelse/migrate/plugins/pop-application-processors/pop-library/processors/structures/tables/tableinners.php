<?php

class PoP_Module_Processor_TableInners extends PoP_Module_Processor_TableInnersBase
{
    public const MODULE_TABLEINNER_MYCONTENT = 'tableinner-mycontent';
    public const MODULE_TABLEINNER_MYHIGHLIGHTS = 'tableinner-myhighlights';
    public const MODULE_TABLEINNER_MYPOSTS = 'tableinner-myposts';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLEINNER_MYCONTENT],
            [self::class, self::MODULE_TABLEINNER_MYHIGHLIGHTS],
            [self::class, self::MODULE_TABLEINNER_MYPOSTS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        // Main layout
        switch ($module[1]) {
            case self::MODULE_TABLEINNER_MYCONTENT:
                $ret[] = [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_EDIT];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::MODULE_LAYOUTPOST_STATUS];
                break;

            case self::MODULE_TABLEINNER_MYHIGHLIGHTS:
                $ret[] = [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT];
                $ret[] = [PoP_Module_Processor_PostDateLayouts::class, PoP_Module_Processor_PostDateLayouts::MODULE_LAYOUTPOST_DATE];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::MODULE_LAYOUTPOST_STATUS];
                break;

            case self::MODULE_TABLEINNER_MYPOSTS:
                $ret[] = [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_EDIT];
                $ret[] = [PoP_Module_Processor_PostDateLayouts::class, PoP_Module_Processor_PostDateLayouts::MODULE_LAYOUTPOST_DATE];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::MODULE_LAYOUTPOST_STATUS];
                break;
        }

        return $ret;
    }
}



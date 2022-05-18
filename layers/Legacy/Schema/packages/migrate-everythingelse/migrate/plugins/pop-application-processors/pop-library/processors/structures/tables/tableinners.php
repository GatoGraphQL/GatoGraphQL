<?php

class PoP_Module_Processor_TableInners extends PoP_Module_Processor_TableInnersBase
{
    public final const MODULE_TABLEINNER_MYCONTENT = 'tableinner-mycontent';
    public final const MODULE_TABLEINNER_MYHIGHLIGHTS = 'tableinner-myhighlights';
    public final const MODULE_TABLEINNER_MYPOSTS = 'tableinner-myposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABLEINNER_MYCONTENT],
            [self::class, self::COMPONENT_TABLEINNER_MYHIGHLIGHTS],
            [self::class, self::COMPONENT_TABLEINNER_MYPOSTS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        // Main layout
        switch ($component[1]) {
            case self::COMPONENT_TABLEINNER_MYCONTENT:
                $ret[] = [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_EDIT];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::COMPONENT_LAYOUTPOST_STATUS];
                break;

            case self::COMPONENT_TABLEINNER_MYHIGHLIGHTS:
                $ret[] = [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT];
                $ret[] = [PoP_Module_Processor_PostDateLayouts::class, PoP_Module_Processor_PostDateLayouts::COMPONENT_LAYOUTPOST_DATE];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::COMPONENT_LAYOUTPOST_STATUS];
                break;

            case self::COMPONENT_TABLEINNER_MYPOSTS:
                $ret[] = [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_POST_EDIT];
                $ret[] = [PoP_Module_Processor_PostDateLayouts::class, PoP_Module_Processor_PostDateLayouts::COMPONENT_LAYOUTPOST_DATE];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::COMPONENT_LAYOUTPOST_STATUS];
                break;
        }

        return $ret;
    }
}



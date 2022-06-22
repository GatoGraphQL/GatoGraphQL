<?php

class PoP_Module_Processor_TableInners extends PoP_Module_Processor_TableInnersBase
{
    public final const COMPONENT_TABLEINNER_MYCONTENT = 'tableinner-mycontent';
    public final const COMPONENT_TABLEINNER_MYHIGHLIGHTS = 'tableinner-myhighlights';
    public final const COMPONENT_TABLEINNER_MYPOSTS = 'tableinner-myposts';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TABLEINNER_MYCONTENT,
            self::COMPONENT_TABLEINNER_MYHIGHLIGHTS,
            self::COMPONENT_TABLEINNER_MYPOSTS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        // Main layout
        switch ($component->name) {
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



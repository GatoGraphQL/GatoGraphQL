<?php

class GD_Custom_EM_Module_Processor_TableInners extends PoP_Module_Processor_TableInnersBase
{
    public final const COMPONENT_TABLEINNER_MYLOCATIONPOSTS = 'tableinner-mylocationposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABLEINNER_MYLOCATIONPOSTS],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        // Main layout
        switch ($component[1]) {
            case self::COMPONENT_TABLEINNER_MYLOCATIONPOSTS:
                $ret[] = [PoP_LocationPostsCreation_Module_Processor_CustomPreviewPostLayouts::class, PoP_LocationPostsCreation_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT];
                $ret[] = [PoP_Module_Processor_PostDateLayouts::class, PoP_Module_Processor_PostDateLayouts::COMPONENT_LAYOUTPOST_DATE];
                $ret[] = [PoP_Module_Processor_PostStatusLayouts::class, PoP_Module_Processor_PostStatusLayouts::COMPONENT_LAYOUTPOST_STATUS];
                break;
        }

        return $ret;
    }
}



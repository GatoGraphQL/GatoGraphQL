<?php

class PoP_Module_Processor_PostCommentMaxHeightLayouts extends PoP_Module_Processor_MaxHeightLayoutsBase
{
    public final const COMPONENT_MAXHEIGHT_SUBCOMPONENT_POSTCOMMENTS = 'maxheight-subcomponent-postcomments';
    
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MAXHEIGHT_SUBCOMPONENT_POSTCOMMENTS],
        );
    }

    public function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MAXHEIGHT_SUBCOMPONENT_POSTCOMMENTS:
                $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_SUBCOMPONENT_POSTCOMMENTS];
                break;
        }

        return $ret;
    }

    public function getMaxheight(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_MAXHEIGHT_SUBCOMPONENT_POSTCOMMENTS:
                return '300';
        }

        return parent::getMaxheight($component, $props);
    }
}




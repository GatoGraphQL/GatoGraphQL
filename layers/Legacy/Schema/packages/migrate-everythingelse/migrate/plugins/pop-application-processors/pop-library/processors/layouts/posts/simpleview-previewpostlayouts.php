<?php

class PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts extends PoP_Module_Processor_CustomSimpleViewPreviewPostLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWPOST_SIMPLEVIEW = 'layout-previewpost-simpleview';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_MULTIPLESIMPLEVIEW = 'layout-previewpost-multiplesimpleview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_SIMPLEVIEW],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_MULTIPLESIMPLEVIEW],
        );
    }

    public function getQuicklinkgroupTopSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_SIMPLEVIEW:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_MULTIPLESIMPLEVIEW:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubcomponent($component);
    }

    public function getAbovecontentSubcomponents(array $component)
    {
        $ret = parent::getAbovecontentSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_MULTIPLESIMPLEVIEW:
                $ret[] = [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW_ABOVECONTENT];
                break;
        }

        return $ret;
    }
}



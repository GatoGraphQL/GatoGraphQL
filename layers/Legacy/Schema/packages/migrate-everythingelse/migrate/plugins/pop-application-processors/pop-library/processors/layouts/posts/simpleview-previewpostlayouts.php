<?php

class PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts extends PoP_Module_Processor_CustomSimpleViewPreviewPostLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW = 'layout-previewpost-simpleview';
    public final const MODULE_LAYOUT_PREVIEWPOST_MULTIPLESIMPLEVIEW = 'layout-previewpost-multiplesimpleview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_MULTIPLESIMPLEVIEW],
        );
    }

    public function getQuicklinkgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW:
            case self::MODULE_LAYOUT_PREVIEWPOST_MULTIPLESIMPLEVIEW:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubmodule($component);
    }

    public function getAbovecontentSubmodules(array $component)
    {
        $ret = parent::getAbovecontentSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_MULTIPLESIMPLEVIEW:
                $ret[] = [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW_ABOVECONTENT];
                break;
        }

        return $ret;
    }
}



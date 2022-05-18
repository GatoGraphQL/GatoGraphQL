<?php

class GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts extends PoP_Module_Processor_CustomSimpleViewPreviewPostLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW = 'layout-previewpost-event-simpleview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW],
        );
    }

    public function getQuicklinkgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubmodule($component);
    }

    public function getAbovecontentSubmodules(array $component)
    {
        $ret = parent::getAbovecontentSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW:
                $ret[] = [GD_EM_Module_Processor_EventMultipleComponents::class, GD_EM_Module_Processor_EventMultipleComponents::COMPONENT_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS];
                break;
        }

        return $ret;
    }
}



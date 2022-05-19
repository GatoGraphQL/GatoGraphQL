<?php

class PoPSFEM_Module_Processor_SimpleViewPreviewPostLayouts extends PoP_Module_Processor_CustomSimpleViewPreviewPostLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_SIMPLEVIEW = 'layout-previewpost-locationpost-simpleview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_SIMPLEVIEW],
        );
    }

    public function getQuicklinkgroupTopSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_SIMPLEVIEW:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubcomponent($component);
    }

    public function getAbovecontentSubcomponents(array $component)
    {
        $ret = parent::getAbovecontentSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_SIMPLEVIEW:
                if (defined('POP_EVENTSPROCESSORS_INITIALIZED')) {
                    $ret[] = [GD_EM_Module_Processor_EventMultipleComponents::class, GD_EM_Module_Processor_EventMultipleComponents::COMPONENT_MULTICOMPONENT_LOCATION];
                }
                break;
        }

        return $ret;
    }
}



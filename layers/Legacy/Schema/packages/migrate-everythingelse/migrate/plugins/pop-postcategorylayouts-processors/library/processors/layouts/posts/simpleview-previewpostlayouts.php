<?php

class PoP_PostCategoryLayouts_Module_Processor_SimpleViewPreviewPostLayouts extends PoP_Module_Processor_CustomSimpleViewPreviewPostLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWPOST_SIMPLEVIEW_FEATUREIMAGE = 'layout-previewpost-simpleview-featureimage';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_PREVIEWPOST_SIMPLEVIEW_FEATUREIMAGE,
        );
    }

    public function getQuicklinkgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_SIMPLEVIEW_FEATUREIMAGE:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubcomponent($component);
    }

    public function getTopSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getTopSubcomponents($component);
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_SIMPLEVIEW_FEATUREIMAGE:
                $ret[] = [GD_Custom_Module_Processor_PostThumbLayoutWrappers::class, GD_Custom_Module_Processor_PostThumbLayoutWrappers::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE];
                break;
        }

        return $ret;
    }

    public function getAbovecontentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {

        // $ret = parent::getAbovecontentSubcomponents($component);
        $ret = array();

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_SIMPLEVIEW_FEATUREIMAGE:
                $ret[] = [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW_ABOVECONTENT];
                break;
        }

        return $ret;
    }
}



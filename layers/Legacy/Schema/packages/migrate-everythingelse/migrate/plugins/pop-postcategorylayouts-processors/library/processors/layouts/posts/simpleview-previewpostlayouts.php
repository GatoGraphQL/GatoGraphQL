<?php

class PoP_PostCategoryLayouts_Module_Processor_SimpleViewPreviewPostLayouts extends PoP_Module_Processor_CustomSimpleViewPreviewPostLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW_FEATUREIMAGE = 'layout-previewpost-simpleview-featureimage';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW_FEATUREIMAGE],
        );
    }

    public function getQuicklinkgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW_FEATUREIMAGE:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubmodule($module);
    }

    public function getTopSubmodules(array $module)
    {
        $ret = parent::getTopSubmodules($module);
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW_FEATUREIMAGE:
                $ret[] = [GD_Custom_Module_Processor_PostThumbLayoutWrappers::class, GD_Custom_Module_Processor_PostThumbLayoutWrappers::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE];
                break;
        }

        return $ret;
    }

    public function getAbovecontentSubmodules(array $module)
    {

        // $ret = parent::getAbovecontentSubmodules($module);
        $ret = array();

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW_FEATUREIMAGE:
                $ret[] = [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW_ABOVECONTENT];
                break;
        }

        return $ret;
    }
}



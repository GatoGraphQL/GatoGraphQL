<?php

class PoP_LocationPostsCreation_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT = 'layout-previewpost-locationpost-edit';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT],
        );
    }



    public function getUrlField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT:
                return 'editURL';
        }

        return parent::getUrlField($module);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($module, $props);
    }

    public function getQuicklinkgroupBottomSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POSTEDIT];
        }

        return parent::getQuicklinkgroupBottomSubmodule($module);
    }

    public function getPostThumbSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT];
        }

        return parent::getPostThumbSubmodule($module);
    }

    public function authorPositions(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT:
                return array();
        }

        return parent::authorPositions($module);
    }

    public function horizontalMediaLayout(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT:
                return true;
        }

        return parent::horizontalMediaLayout($module);
    }
}



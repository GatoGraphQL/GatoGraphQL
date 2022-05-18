<?php

class PoP_LocationPostsCreation_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT = 'layout-previewpost-locationpost-edit';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT],
        );
    }



    public function getUrlField(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT:
                return 'editURL';
        }

        return parent::getUrlField($component);
    }

    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getQuicklinkgroupBottomSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POSTEDIT];
        }

        return parent::getQuicklinkgroupBottomSubmodule($component);
    }

    public function getPostThumbSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT];
        }

        return parent::getPostThumbSubmodule($component);
    }

    public function authorPositions(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT:
                return array();
        }

        return parent::authorPositions($component);
    }

    public function horizontalMediaLayout(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT:
                return true;
        }

        return parent::horizontalMediaLayout($component);
    }
}



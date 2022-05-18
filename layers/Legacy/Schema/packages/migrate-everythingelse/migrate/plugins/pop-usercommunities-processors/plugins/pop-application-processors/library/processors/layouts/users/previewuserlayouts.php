<?php

class PoP_UserCommunities_Module_Processor_PreviewUserLayouts extends PoP_Module_Processor_CustomPreviewUserLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWUSER_EDITMEMBERS = 'layout-previewuser-editmembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PREVIEWUSER_EDITMEMBERS],
        );
    }

    public function getQuicklinkgroupBottomSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_EDITMEMBERS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_USER_EDITMEMBERS];
        }

        return parent::getQuicklinkgroupBottomSubmodule($component);
    }

    public function getUseravatarSubmodule(array $component)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($component[1]) {
                case self::COMPONENT_LAYOUT_PREVIEWUSER_EDITMEMBERS:
                    return [PoP_Module_Processor_UserAvatarLayouts::class, PoP_Module_Processor_UserAvatarLayouts::COMPONENT_LAYOUT_USERAVATAR_60_RESPONSIVE];
            }
        }

        return parent::getUseravatarSubmodule($component);
    }

    public function showShortDescription(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_EDITMEMBERS:
                return false;
        }

        return parent::showShortDescription($component);
    }

    public function horizontalMediaLayout(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_EDITMEMBERS:
                return true;
        }

        return parent::horizontalMediaLayout($component);
    }
}



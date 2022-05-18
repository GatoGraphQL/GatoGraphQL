<?php

class PoP_UserCommunities_Module_Processor_PreviewUserLayouts extends PoP_Module_Processor_CustomPreviewUserLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWUSER_EDITMEMBERS = 'layout-previewuser-editmembers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_EDITMEMBERS],
        );
    }

    public function getQuicklinkgroupBottomSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_EDITMEMBERS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USER_EDITMEMBERS];
        }

        return parent::getQuicklinkgroupBottomSubmodule($componentVariation);
    }

    public function getUseravatarSubmodule(array $componentVariation)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($componentVariation[1]) {
                case self::MODULE_LAYOUT_PREVIEWUSER_EDITMEMBERS:
                    return [PoP_Module_Processor_UserAvatarLayouts::class, PoP_Module_Processor_UserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_60_RESPONSIVE];
            }
        }

        return parent::getUseravatarSubmodule($componentVariation);
    }

    public function showShortDescription(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_EDITMEMBERS:
                return false;
        }

        return parent::showShortDescription($componentVariation);
    }

    public function horizontalMediaLayout(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_EDITMEMBERS:
                return true;
        }

        return parent::horizontalMediaLayout($componentVariation);
    }
}



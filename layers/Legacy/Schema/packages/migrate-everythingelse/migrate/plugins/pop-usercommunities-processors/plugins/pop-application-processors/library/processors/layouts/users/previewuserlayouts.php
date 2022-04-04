<?php

class PoP_UserCommunities_Module_Processor_PreviewUserLayouts extends PoP_Module_Processor_CustomPreviewUserLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWUSER_EDITMEMBERS = 'layout-previewuser-editmembers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_EDITMEMBERS],
        );
    }

    public function getQuicklinkgroupBottomSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_EDITMEMBERS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USER_EDITMEMBERS];
        }

        return parent::getQuicklinkgroupBottomSubmodule($module);
    }

    public function getUseravatarSubmodule(array $module)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($module[1]) {
                case self::MODULE_LAYOUT_PREVIEWUSER_EDITMEMBERS:
                    return [PoP_Module_Processor_UserAvatarLayouts::class, PoP_Module_Processor_UserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_60_RESPONSIVE];
            }
        }

        return parent::getUseravatarSubmodule($module);
    }

    public function showShortDescription(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_EDITMEMBERS:
                return false;
        }

        return parent::showShortDescription($module);
    }

    public function horizontalMediaLayout(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_EDITMEMBERS:
                return true;
        }

        return parent::horizontalMediaLayout($module);
    }
}



<?php

class GD_EM_Module_Processor_CustomPreviewUserLayouts extends PoP_Module_Processor_CustomPreviewUserLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS = 'layout-previewuser-mapdetails';
    public final const MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS = 'layout-previewuser-horizontalmapdetails';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS],
        );
    }

    public function getQuicklinkgroupBottomSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USERBOTTOM];
        }

        return parent::getQuicklinkgroupBottomSubmodule($module);
    }

    public function getQuicklinkgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USER];
        }

        return parent::getQuicklinkgroupTopSubmodule($module);
    }

    public function getBelowexcerptLayoutSubmodules(array $module)
    {
        $ret = parent::getBelowexcerptLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS];
                break;
        }

        return $ret;
    }

    public function getUseravatarSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS:
                return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_120_RESPONSIVE];
            
            case self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_40];
        }

        return parent::getUseravatarSubmodule($module);
    }

    public function horizontalMediaLayout(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                return true;
        }

        return parent::horizontalMediaLayout($module);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                $ret[GD_JS_CLASSES]['quicklinkgroup-bottom'] = 'icon-only pull-right';
                break;
        }

        return $ret;
    }
}



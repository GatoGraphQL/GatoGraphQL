<?php

class GD_EM_Module_Processor_CustomPreviewUserLayouts extends PoP_Module_Processor_CustomPreviewUserLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS = 'layout-previewuser-mapdetails';
    public final const MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS = 'layout-previewuser-horizontalmapdetails';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS],
        );
    }

    public function getQuicklinkgroupBottomSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USERBOTTOM];
        }

        return parent::getQuicklinkgroupBottomSubmodule($component);
    }

    public function getQuicklinkgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USER];
        }

        return parent::getQuicklinkgroupTopSubmodule($component);
    }

    public function getBelowexcerptLayoutSubmodules(array $component)
    {
        $ret = parent::getBelowexcerptLayoutSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS];
                break;
        }

        return $ret;
    }

    public function getUseravatarSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS:
                return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_120_RESPONSIVE];
            
            case self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_40];
        }

        return parent::getUseravatarSubmodule($component);
    }

    public function horizontalMediaLayout(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                return true;
        }

        return parent::horizontalMediaLayout($component);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                $ret[GD_JS_CLASSES]['quicklinkgroup-bottom'] = 'icon-only pull-right';
                break;
        }

        return $ret;
    }
}



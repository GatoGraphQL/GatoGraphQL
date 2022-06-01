<?php

class GD_EM_Module_Processor_CustomPreviewUserLayouts extends PoP_Module_Processor_CustomPreviewUserLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWUSER_MAPDETAILS = 'layout-previewuser-mapdetails';
    public final const COMPONENT_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS = 'layout-previewuser-horizontalmapdetails';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PREVIEWUSER_MAPDETAILS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS],
        );
    }

    public function getQuicklinkgroupBottomSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_USERBOTTOM];
        }

        return parent::getQuicklinkgroupBottomSubcomponent($component);
    }

    public function getQuicklinkgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_USER];
        }

        return parent::getQuicklinkgroupTopSubcomponent($component);
    }

    public function getBelowexcerptLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getBelowexcerptLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS];
                break;
        }

        return $ret;
    }

    public function getUseravatarSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_MAPDETAILS:
                return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::COMPONENT_LAYOUT_USERAVATAR_120_RESPONSIVE];
            
            case self::COMPONENT_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::COMPONENT_LAYOUT_USERAVATAR_40];
        }

        return parent::getUseravatarSubcomponent($component);
    }

    public function horizontalMediaLayout(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                return true;
        }

        return parent::horizontalMediaLayout($component);
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_MAPDETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_HORIZONTALMAPDETAILS:
                $ret[GD_JS_CLASSES]['quicklinkgroup-bottom'] = 'icon-only pull-right';
                break;
        }

        return $ret;
    }
}



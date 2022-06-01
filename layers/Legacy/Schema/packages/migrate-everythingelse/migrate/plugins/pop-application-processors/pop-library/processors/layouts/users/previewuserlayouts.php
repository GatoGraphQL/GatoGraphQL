<?php

class PoP_Module_Processor_CustomPreviewUserLayouts extends PoP_Module_Processor_CustomPreviewUserLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWUSER_NAVIGATOR = 'layout-previewuser-navigator';
    public final const COMPONENT_LAYOUT_PREVIEWUSER_ADDONS = 'layout-previewuser-addons';
    public final const COMPONENT_LAYOUT_PREVIEWUSER_DETAILS = 'layout-previewuser-details';
    public final const COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL = 'layout-previewuser-thumbnail';
    public final const COMPONENT_LAYOUT_PREVIEWUSER_LIST = 'layout-previewuser-list';
    public final const COMPONENT_LAYOUT_PREVIEWUSER_POPOVER = 'layout-previewuser-popover';
    public final const COMPONENT_LAYOUT_PREVIEWUSER_POSTAUTHOR = 'layout-previewuser-postauthor';
    public final const COMPONENT_LAYOUT_PREVIEWUSER_HEADER = 'layout-previewuser-header';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PREVIEWUSER_NAVIGATOR],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWUSER_ADDONS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWUSER_LIST],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWUSER_POPOVER],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWUSER_POSTAUTHOR],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWUSER_HEADER],
        );
    }

    public function getQuicklinkgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_NAVIGATOR:
                // case self::COMPONENT_LAYOUT_PREVIEWUSER_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POPOVER:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POSTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_LIST:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_USER];
        }

        return parent::getQuicklinkgroupTopSubcomponent($component);
    }

    public function getTitleHtmlmarkup(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($component, $props);
    }

    public function getQuicklinkgroupBottomSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_NAVIGATOR:
                // case self::COMPONENT_LAYOUT_PREVIEWUSER_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POPOVER:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POSTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_LIST:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_USERBOTTOM];
        }

        return parent::getQuicklinkgroupBottomSubcomponent($component);
    }

    public function getBelowavatarLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getBelowavatarLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
                if (defined('POP_LOCATIONSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS];
                }
                break;
        }

        return $ret;
    }

    public function getBelowexcerptLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getBelowexcerptLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL:
                if (defined('POP_LOCATIONSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS];
                }
                break;
        }

        return $ret;
    }

    public function getUseravatarSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($component->name) {
                case self::COMPONENT_LAYOUT_PREVIEWUSER_POSTAUTHOR:
                case self::COMPONENT_LAYOUT_PREVIEWUSER_NAVIGATOR:
                case self::COMPONENT_LAYOUT_PREVIEWUSER_ADDONS:
                case self::COMPONENT_LAYOUT_PREVIEWUSER_LIST:
                    return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::COMPONENT_LAYOUT_USERAVATAR_40];

                case self::COMPONENT_LAYOUT_PREVIEWUSER_POPOVER:
                    return [PoP_Module_Processor_UserAvatarLayouts::class, PoP_Module_Processor_UserAvatarLayouts::COMPONENT_LAYOUT_USERAVATAR_60];

                case self::COMPONENT_LAYOUT_PREVIEWUSER_HEADER:
                    return [PoP_Module_Processor_UserAvatarLayouts::class, PoP_Module_Processor_UserAvatarLayouts::COMPONENT_LAYOUT_USERAVATAR_60_RESPONSIVE];

                case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
                case self::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL:
                    return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::COMPONENT_LAYOUT_USERAVATAR_150_RESPONSIVE];
            }
        }

        return parent::getUseravatarSubcomponent($component);
    }

    public function showExcerpt(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
                return true;
        }

        return parent::showExcerpt($component);
    }

    public function horizontalLayout(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
                return true;
        }

        return parent::horizontalLayout($component);
    }

    public function horizontalMediaLayout(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POPOVER:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POSTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_HEADER:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_LIST:
                return true;
        }

        return parent::horizontalMediaLayout($component);
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_NAVIGATOR:
                // case self::COMPONENT_LAYOUT_PREVIEWUSER_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POPOVER:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POSTAUTHOR:
                $ret[GD_JS_CLASSES]['quicklinkgroup-bottom'] = 'icon-only pull-right';
                break;
        }
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL:
                $ret[GD_JS_CLASSES]['belowavatar'] = 'bg-info text-info belowavatar';
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_HEADER:
                $this->appendProp($component, $props, 'class', 'alert alert-info alert-sm');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



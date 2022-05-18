<?php

class PoP_Module_Processor_CustomPreviewUserLayouts extends PoP_Module_Processor_CustomPreviewUserLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWUSER_NAVIGATOR = 'layout-previewuser-navigator';
    public final const MODULE_LAYOUT_PREVIEWUSER_ADDONS = 'layout-previewuser-addons';
    public final const MODULE_LAYOUT_PREVIEWUSER_DETAILS = 'layout-previewuser-details';
    public final const MODULE_LAYOUT_PREVIEWUSER_THUMBNAIL = 'layout-previewuser-thumbnail';
    public final const MODULE_LAYOUT_PREVIEWUSER_LIST = 'layout-previewuser-list';
    public final const MODULE_LAYOUT_PREVIEWUSER_POPOVER = 'layout-previewuser-popover';
    public final const MODULE_LAYOUT_PREVIEWUSER_POSTAUTHOR = 'layout-previewuser-postauthor';
    public final const MODULE_LAYOUT_PREVIEWUSER_HEADER = 'layout-previewuser-header';

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

    public function getQuicklinkgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_NAVIGATOR:
                // case self::COMPONENT_LAYOUT_PREVIEWUSER_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POPOVER:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POSTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_LIST:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_USER];
        }

        return parent::getQuicklinkgroupTopSubmodule($component);
    }

    public function getTitleHtmlmarkup(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($component, $props);
    }

    public function getQuicklinkgroupBottomSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_NAVIGATOR:
                // case self::COMPONENT_LAYOUT_PREVIEWUSER_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POPOVER:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POSTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_LIST:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_USERBOTTOM];
        }

        return parent::getQuicklinkgroupBottomSubmodule($component);
    }

    public function getBelowavatarLayoutSubmodules(array $component)
    {
        $ret = parent::getBelowavatarLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
                if (defined('POP_LOCATIONSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS];
                }
                break;
        }

        return $ret;
    }

    public function getBelowexcerptLayoutSubmodules(array $component)
    {
        $ret = parent::getBelowexcerptLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL:
                if (defined('POP_LOCATIONSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS];
                }
                break;
        }

        return $ret;
    }

    public function getUseravatarSubmodule(array $component)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($component[1]) {
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

        return parent::getUseravatarSubmodule($component);
    }

    public function showExcerpt(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
                return true;
        }

        return parent::showExcerpt($component);
    }

    public function horizontalLayout(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
                return true;
        }

        return parent::horizontalLayout($component);
    }

    public function horizontalMediaLayout(array $component)
    {
        switch ($component[1]) {
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

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_NAVIGATOR:
                // case self::COMPONENT_LAYOUT_PREVIEWUSER_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POPOVER:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_POSTAUTHOR:
                $ret[GD_JS_CLASSES]['quicklinkgroup-bottom'] = 'icon-only pull-right';
                break;
        }
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL:
                $ret[GD_JS_CLASSES]['belowavatar'] = 'bg-info text-info belowavatar';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_HEADER:
                $this->appendProp($component, $props, 'class', 'alert alert-info alert-sm');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



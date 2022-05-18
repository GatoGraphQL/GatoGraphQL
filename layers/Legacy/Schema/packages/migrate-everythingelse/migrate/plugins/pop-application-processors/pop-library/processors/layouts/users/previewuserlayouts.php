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

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_NAVIGATOR],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_ADDONS],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_DETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_THUMBNAIL],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_LIST],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_POPOVER],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_POSTAUTHOR],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_HEADER],
        );
    }

    public function getQuicklinkgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_NAVIGATOR:
                // case self::MODULE_LAYOUT_PREVIEWUSER_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWUSER_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWUSER_POPOVER:
            case self::MODULE_LAYOUT_PREVIEWUSER_POSTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWUSER_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_LIST:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USER];
        }

        return parent::getQuicklinkgroupTopSubmodule($componentVariation);
    }

    public function getTitleHtmlmarkup(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($componentVariation, $props);
    }

    public function getQuicklinkgroupBottomSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_NAVIGATOR:
                // case self::MODULE_LAYOUT_PREVIEWUSER_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWUSER_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWUSER_POPOVER:
            case self::MODULE_LAYOUT_PREVIEWUSER_POSTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWUSER_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_LIST:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USERBOTTOM];
        }

        return parent::getQuicklinkgroupBottomSubmodule($componentVariation);
    }

    public function getBelowavatarLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getBelowavatarLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_DETAILS:
                if (defined('POP_LOCATIONSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS];
                }
                break;
        }

        return $ret;
    }

    public function getBelowexcerptLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getBelowexcerptLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_THUMBNAIL:
                if (defined('POP_LOCATIONSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS];
                }
                break;
        }

        return $ret;
    }

    public function getUseravatarSubmodule(array $componentVariation)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($componentVariation[1]) {
                case self::MODULE_LAYOUT_PREVIEWUSER_POSTAUTHOR:
                case self::MODULE_LAYOUT_PREVIEWUSER_NAVIGATOR:
                case self::MODULE_LAYOUT_PREVIEWUSER_ADDONS:
                case self::MODULE_LAYOUT_PREVIEWUSER_LIST:
                    return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_40];

                case self::MODULE_LAYOUT_PREVIEWUSER_POPOVER:
                    return [PoP_Module_Processor_UserAvatarLayouts::class, PoP_Module_Processor_UserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_60];

                case self::MODULE_LAYOUT_PREVIEWUSER_HEADER:
                    return [PoP_Module_Processor_UserAvatarLayouts::class, PoP_Module_Processor_UserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_60_RESPONSIVE];

                case self::MODULE_LAYOUT_PREVIEWUSER_DETAILS:
                case self::MODULE_LAYOUT_PREVIEWUSER_THUMBNAIL:
                    return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_150_RESPONSIVE];
            }
        }

        return parent::getUseravatarSubmodule($componentVariation);
    }

    public function showExcerpt(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_DETAILS:
                return true;
        }

        return parent::showExcerpt($componentVariation);
    }

    public function horizontalLayout(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_DETAILS:
                return true;
        }

        return parent::horizontalLayout($componentVariation);
    }

    public function horizontalMediaLayout(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_POPOVER:
            case self::MODULE_LAYOUT_PREVIEWUSER_POSTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWUSER_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWUSER_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWUSER_HEADER:
            case self::MODULE_LAYOUT_PREVIEWUSER_LIST:
                return true;
        }

        return parent::horizontalMediaLayout($componentVariation);
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_NAVIGATOR:
                // case self::MODULE_LAYOUT_PREVIEWUSER_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWUSER_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWUSER_POPOVER:
            case self::MODULE_LAYOUT_PREVIEWUSER_POSTAUTHOR:
                $ret[GD_JS_CLASSES]['quicklinkgroup-bottom'] = 'icon-only pull-right';
                break;
        }
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_THUMBNAIL:
                $ret[GD_JS_CLASSES]['belowavatar'] = 'bg-info text-info belowavatar';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_HEADER:
                $this->appendProp($componentVariation, $props, 'class', 'alert alert-info alert-sm');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



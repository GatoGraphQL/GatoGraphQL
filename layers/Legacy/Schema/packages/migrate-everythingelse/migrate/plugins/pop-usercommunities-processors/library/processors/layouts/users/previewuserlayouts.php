<?php

class GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts extends PoP_Module_Processor_CustomPreviewUserLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_NAVIGATOR = 'layout-previewuser-community-navigator';
    public final const MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_ADDONS = 'layout-previewuser-community-addons';
    public final const MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_DETAILS = 'layout-previewuser-community-details';
    public final const MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_THUMBNAIL = 'layout-previewuser-community-thumbnail';
    public final const MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_LIST = 'layout-previewuser-community-list';
    public final const MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_MAPDETAILS = 'layout-previewuser-community-mapdetails';
    public final const MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POPOVER = 'layout-previewuser-community-popover';
    public final const MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_COMMUNITIES = 'layout-previewuser-community-communities';
    public final const MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POSTAUTHOR = 'layout-previewuser-community-postauthor';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_NAVIGATOR],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_ADDONS],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_DETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_THUMBNAIL],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_LIST],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_MAPDETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POPOVER],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_COMMUNITIES],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POSTAUTHOR],
        );
    }

    public function getQuicklinkgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POPOVER:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_COMMUNITIES:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POSTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_LIST:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USER];
        }

        return parent::getQuicklinkgroupTopSubmodule($componentVariation);
    }

    public function getTitleHtmlmarkup(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($componentVariation, $props);
    }

    public function getQuicklinkgroupBottomSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POPOVER:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_COMMUNITIES:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POSTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_LIST:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USERBOTTOM];
        }

        return parent::getQuicklinkgroupBottomSubmodule($componentVariation);
    }

    public function getBelowexcerptLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getBelowexcerptLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_THUMBNAIL:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS];
                break;

            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_MAPDETAILS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS];
                break;

            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_DETAILS:
                $ret[] = [GD_URE_Module_Processor_MembersLayoutWrappers::class, GD_URE_Module_Processor_MembersLayoutWrappers::MODULE_URE_LAYOUTWRAPPER_COMMUNITYMEMBERS];
                break;
        }

        return $ret;
    }

    public function getBelowavatarLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getBelowavatarLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_DETAILS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS];
                break;
        }

        return $ret;
    }

    public function getUseravatarSubmodule(array $componentVariation)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($componentVariation[1]) {
                case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_COMMUNITIES:
                case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POSTAUTHOR:
                case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_NAVIGATOR:
                case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_ADDONS:
                case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_LIST:
                    return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_40];

                case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POPOVER:
                    return [PoP_Module_Processor_UserAvatarLayouts::class, PoP_Module_Processor_UserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_60];

                case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_MAPDETAILS:
                    return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_120_RESPONSIVE];

                case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_DETAILS:
                case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_THUMBNAIL:
                    return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_150_RESPONSIVE];
            }
        }

        return parent::getUseravatarSubmodule($componentVariation);
    }


    public function showExcerpt(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_DETAILS:
                return true;
        }

        return parent::showExcerpt($componentVariation);
    }

    public function horizontalLayout(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_DETAILS:
                return true;
        }

        return parent::horizontalLayout($componentVariation);
    }

    public function horizontalMediaLayout(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POPOVER:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_COMMUNITIES:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POSTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_LIST:
                return true;
        }

        return parent::horizontalMediaLayout($componentVariation);
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_MAPDETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POPOVER:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_COMMUNITIES:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_POSTAUTHOR:
                $ret[GD_JS_CLASSES]['quicklinkgroup-bottom'] = 'icon-only pull-right';
                break;
        }
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_MAPDETAILS:
                $ret[GD_JS_CLASSES]['belowavatar'] = 'bg-info text-info belowavatar';
                break;
        }

        return $ret;
    }
}



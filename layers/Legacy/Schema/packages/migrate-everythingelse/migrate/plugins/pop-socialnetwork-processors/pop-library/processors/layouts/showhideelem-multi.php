<?php

class PoP_Module_Processor_ShowHideElemMultiStyleLayouts extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_LAYOUT_FOLLOWUSER_STYLES = 'layout-followuser-styles';
    public const MODULE_LAYOUT_UNFOLLOWUSER_STYLES = 'layout-unfollowuser-styles';
    public const MODULE_LAYOUT_RECOMMENDPOST_STYLES = 'layout-recommendposts-styles';
    public const MODULE_LAYOUT_UNRECOMMENDPOST_STYLES = 'layout-unrecommendposts-styles';
    public const MODULE_LAYOUT_SUBSCRIBETOTAG_STYLES = 'layout-subscribetotag-styles';
    public const MODULE_LAYOUT_UNSUBSCRIBEFROMTAG_STYLES = 'layout-unsubscribefromtag-styles';
    public const MODULE_LAYOUT_UPVOTEPOST_STYLES = 'layout-upvoteposts-styles';
    public const MODULE_LAYOUT_UNDOUPVOTEPOST_STYLES = 'layout-undoupvoteposts-styles';
    public const MODULE_LAYOUT_DOWNVOTEPOST_STYLES = 'layout-downvoteposts-styles';
    public const MODULE_LAYOUT_UNDODOWNVOTEPOST_STYLES = 'layout-undodownvoteposts-styles';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FOLLOWUSER_STYLES],
            [self::class, self::MODULE_LAYOUT_UNFOLLOWUSER_STYLES],
            [self::class, self::MODULE_LAYOUT_RECOMMENDPOST_STYLES],
            [self::class, self::MODULE_LAYOUT_UNRECOMMENDPOST_STYLES],
            [self::class, self::MODULE_LAYOUT_SUBSCRIBETOTAG_STYLES],
            [self::class, self::MODULE_LAYOUT_UNSUBSCRIBEFROMTAG_STYLES],
            [self::class, self::MODULE_LAYOUT_UPVOTEPOST_STYLES],
            [self::class, self::MODULE_LAYOUT_UNDOUPVOTEPOST_STYLES],
            [self::class, self::MODULE_LAYOUT_DOWNVOTEPOST_STYLES],
            [self::class, self::MODULE_LAYOUT_UNDODOWNVOTEPOST_STYLES],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_FOLLOWUSER_STYLES:
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_FOLLOWUSER_HIDE_STYLES];
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_UNFOLLOWUSER_SHOW_STYLES];
                break;

            case self::MODULE_LAYOUT_UNFOLLOWUSER_STYLES:
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_UNFOLLOWUSER_HIDE_STYLES];
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_FOLLOWUSER_SHOW_STYLES];
                break;

            case self::MODULE_LAYOUT_RECOMMENDPOST_STYLES:
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_RECOMMENDPOST_HIDE_STYLES];
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES];
                break;

            case self::MODULE_LAYOUT_UNRECOMMENDPOST_STYLES:
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES];
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_RECOMMENDPOST_SHOW_STYLES];
                break;

            case self::MODULE_LAYOUT_SUBSCRIBETOTAG_STYLES:
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_SUBSCRIBETOTAG_HIDE_STYLES];
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_UNSUBSCRIBEFROMTAG_SHOW_STYLES];
                break;

            case self::MODULE_LAYOUT_UNSUBSCRIBEFROMTAG_STYLES:
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_UNSUBSCRIBEFROMTAG_HIDE_STYLES];
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_SUBSCRIBETOTAG_SHOW_STYLES];
                break;

            case self::MODULE_LAYOUT_UPVOTEPOST_STYLES:
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_UPVOTEPOST_HIDE_STYLES];
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES];
                break;

            case self::MODULE_LAYOUT_UNDOUPVOTEPOST_STYLES:
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES];
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_UPVOTEPOST_SHOW_STYLES];
                break;

            case self::MODULE_LAYOUT_DOWNVOTEPOST_STYLES:
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_DOWNVOTEPOST_HIDE_STYLES];
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES];
                break;

            case self::MODULE_LAYOUT_UNDODOWNVOTEPOST_STYLES:
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES];
                $ret[] = [PoP_Module_Processor_FunctionLayouts::class, PoP_Module_Processor_FunctionLayouts::MODULE_LAYOUT_DOWNVOTEPOST_SHOW_STYLES];
                break;
        }

        return $ret;
    }
}




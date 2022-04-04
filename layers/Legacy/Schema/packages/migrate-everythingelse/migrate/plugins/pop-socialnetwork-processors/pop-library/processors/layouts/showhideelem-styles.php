<?php

class PoP_Module_Processor_FunctionLayouts extends PoP_Module_Processor_StylesLayoutsBase
{
    public final const MODULE_LAYOUT_FOLLOWUSER_SHOW_STYLES = 'layout-followuser-show-styles';
    public final const MODULE_LAYOUT_FOLLOWUSER_HIDE_STYLES = 'layout-followuser-hide-styles';
    public final const MODULE_LAYOUT_UNFOLLOWUSER_SHOW_STYLES = 'layout-unfollowuser-show-styles';
    public final const MODULE_LAYOUT_UNFOLLOWUSER_HIDE_STYLES = 'layout-unfollowuser-hide-styles';
    public final const MODULE_LAYOUT_RECOMMENDPOST_SHOW_STYLES = 'layout-recommendposts-show-styles';
    public final const MODULE_LAYOUT_RECOMMENDPOST_HIDE_STYLES = 'layout-recommendposts-hide-styles';
    public final const MODULE_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES = 'layout-unrecommendposts-show-styles';
    public final const MODULE_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES = 'layout-unrecommendposts-hide-styles';
    public final const MODULE_LAYOUT_SUBSCRIBETOTAG_SHOW_STYLES = 'layout-subscribetotag-show-styles';
    public final const MODULE_LAYOUT_SUBSCRIBETOTAG_HIDE_STYLES = 'layout-subscribetotag-hide-styles';
    public final const MODULE_LAYOUT_UNSUBSCRIBEFROMTAG_SHOW_STYLES = 'layout-unsubscribefromtag-show-styles';
    public final const MODULE_LAYOUT_UNSUBSCRIBEFROMTAG_HIDE_STYLES = 'layout-unsubscribefromtag-hide-styles';
    public final const MODULE_LAYOUT_UPVOTEPOST_SHOW_STYLES = 'layout-upvoteposts-show-styles';
    public final const MODULE_LAYOUT_UPVOTEPOST_HIDE_STYLES = 'layout-upvoteposts-hide-styles';
    public final const MODULE_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES = 'layout-undoupvoteposts-show-styles';
    public final const MODULE_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES = 'layout-undoupvoteposts-hide-styles';
    public final const MODULE_LAYOUT_DOWNVOTEPOST_SHOW_STYLES = 'layout-downvoteposts-show-styles';
    public final const MODULE_LAYOUT_DOWNVOTEPOST_HIDE_STYLES = 'layout-downvoteposts-hide-styles';
    public final const MODULE_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES = 'layout-undodownvoteposts-show-styles';
    public final const MODULE_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES = 'layout-undodownvoteposts-hide-styles';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FOLLOWUSER_SHOW_STYLES],
            [self::class, self::MODULE_LAYOUT_FOLLOWUSER_HIDE_STYLES],
            [self::class, self::MODULE_LAYOUT_UNFOLLOWUSER_SHOW_STYLES],
            [self::class, self::MODULE_LAYOUT_UNFOLLOWUSER_HIDE_STYLES],
            [self::class, self::MODULE_LAYOUT_RECOMMENDPOST_SHOW_STYLES],
            [self::class, self::MODULE_LAYOUT_RECOMMENDPOST_HIDE_STYLES],
            [self::class, self::MODULE_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES],
            [self::class, self::MODULE_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES],
            [self::class, self::MODULE_LAYOUT_SUBSCRIBETOTAG_SHOW_STYLES],
            [self::class, self::MODULE_LAYOUT_SUBSCRIBETOTAG_HIDE_STYLES],
            [self::class, self::MODULE_LAYOUT_UNSUBSCRIBEFROMTAG_SHOW_STYLES],
            [self::class, self::MODULE_LAYOUT_UNSUBSCRIBEFROMTAG_HIDE_STYLES],
            [self::class, self::MODULE_LAYOUT_UPVOTEPOST_SHOW_STYLES],
            [self::class, self::MODULE_LAYOUT_UPVOTEPOST_HIDE_STYLES],
            [self::class, self::MODULE_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES],
            [self::class, self::MODULE_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES],
            [self::class, self::MODULE_LAYOUT_DOWNVOTEPOST_SHOW_STYLES],
            [self::class, self::MODULE_LAYOUT_DOWNVOTEPOST_HIDE_STYLES],
            [self::class, self::MODULE_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES],
            [self::class, self::MODULE_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES],
        );
    }

    public function getElemTarget(array $module, array &$props)
    {
        $targets = array(
            self::MODULE_LAYOUT_FOLLOWUSER_SHOW_STYLES => GD_CLASS_FOLLOWUSER,
            self::MODULE_LAYOUT_UNFOLLOWUSER_SHOW_STYLES => GD_CLASS_UNFOLLOWUSER,
            self::MODULE_LAYOUT_RECOMMENDPOST_SHOW_STYLES => GD_CLASS_RECOMMENDPOST,
            self::MODULE_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES => GD_CLASS_UNRECOMMENDPOST,
            self::MODULE_LAYOUT_SUBSCRIBETOTAG_SHOW_STYLES => GD_CLASS_SUBSCRIBETOTAG,
            self::MODULE_LAYOUT_UNSUBSCRIBEFROMTAG_SHOW_STYLES => GD_CLASS_UNSUBSCRIBEFROMTAG,
            self::MODULE_LAYOUT_UPVOTEPOST_SHOW_STYLES => GD_CLASS_UPVOTEPOST,
            self::MODULE_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES => GD_CLASS_UNDOUPVOTEPOST,
            self::MODULE_LAYOUT_DOWNVOTEPOST_SHOW_STYLES => GD_CLASS_DOWNVOTEPOST,
            self::MODULE_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES => GD_CLASS_UNDODOWNVOTEPOST,
            self::MODULE_LAYOUT_FOLLOWUSER_HIDE_STYLES => GD_CLASS_FOLLOWUSER,
            self::MODULE_LAYOUT_UNFOLLOWUSER_HIDE_STYLES => GD_CLASS_UNFOLLOWUSER,
            self::MODULE_LAYOUT_RECOMMENDPOST_HIDE_STYLES => GD_CLASS_RECOMMENDPOST,
            self::MODULE_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES => GD_CLASS_UNRECOMMENDPOST,
            self::MODULE_LAYOUT_SUBSCRIBETOTAG_HIDE_STYLES => GD_CLASS_SUBSCRIBETOTAG,
            self::MODULE_LAYOUT_UNSUBSCRIBEFROMTAG_HIDE_STYLES => GD_CLASS_UNSUBSCRIBEFROMTAG,
            self::MODULE_LAYOUT_UPVOTEPOST_HIDE_STYLES => GD_CLASS_UPVOTEPOST,
            self::MODULE_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES => GD_CLASS_UNDOUPVOTEPOST,
            self::MODULE_LAYOUT_DOWNVOTEPOST_HIDE_STYLES => GD_CLASS_DOWNVOTEPOST,
            self::MODULE_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES => GD_CLASS_UNDODOWNVOTEPOST,
        );
        if ($target = $targets[$module[1]] ?? null) {
            return '.'.$target;
        }

        return parent::getElemTarget($module, $props);
    }

    public function getElemStyles(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_FOLLOWUSER_SHOW_STYLES:
            case self::MODULE_LAYOUT_UNFOLLOWUSER_SHOW_STYLES:
            case self::MODULE_LAYOUT_RECOMMENDPOST_SHOW_STYLES:
            case self::MODULE_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES:
            case self::MODULE_LAYOUT_SUBSCRIBETOTAG_SHOW_STYLES:
            case self::MODULE_LAYOUT_UNSUBSCRIBEFROMTAG_SHOW_STYLES:
            case self::MODULE_LAYOUT_UPVOTEPOST_SHOW_STYLES:
            case self::MODULE_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES:
            case self::MODULE_LAYOUT_DOWNVOTEPOST_SHOW_STYLES:
            case self::MODULE_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES:
                return array(
                    'display' => 'block'
                );

            case self::MODULE_LAYOUT_FOLLOWUSER_HIDE_STYLES:
            case self::MODULE_LAYOUT_UNFOLLOWUSER_HIDE_STYLES:
            case self::MODULE_LAYOUT_RECOMMENDPOST_HIDE_STYLES:
            case self::MODULE_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES:
            case self::MODULE_LAYOUT_SUBSCRIBETOTAG_HIDE_STYLES:
            case self::MODULE_LAYOUT_UNSUBSCRIBEFROMTAG_HIDE_STYLES:
            case self::MODULE_LAYOUT_UPVOTEPOST_HIDE_STYLES:
            case self::MODULE_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES:
            case self::MODULE_LAYOUT_DOWNVOTEPOST_HIDE_STYLES:
            case self::MODULE_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES:
                return array(
                    'display' => 'none'
                );
        }

        return parent::getElemStyles($module, $props);
    }
}




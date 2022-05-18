<?php

class PoP_Module_Processor_FunctionLayouts extends PoP_Module_Processor_StylesLayoutsBase
{
    public final const COMPONENT_LAYOUT_FOLLOWUSER_SHOW_STYLES = 'layout-followuser-show-styles';
    public final const COMPONENT_LAYOUT_FOLLOWUSER_HIDE_STYLES = 'layout-followuser-hide-styles';
    public final const COMPONENT_LAYOUT_UNFOLLOWUSER_SHOW_STYLES = 'layout-unfollowuser-show-styles';
    public final const COMPONENT_LAYOUT_UNFOLLOWUSER_HIDE_STYLES = 'layout-unfollowuser-hide-styles';
    public final const COMPONENT_LAYOUT_RECOMMENDPOST_SHOW_STYLES = 'layout-recommendposts-show-styles';
    public final const COMPONENT_LAYOUT_RECOMMENDPOST_HIDE_STYLES = 'layout-recommendposts-hide-styles';
    public final const COMPONENT_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES = 'layout-unrecommendposts-show-styles';
    public final const COMPONENT_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES = 'layout-unrecommendposts-hide-styles';
    public final const COMPONENT_LAYOUT_SUBSCRIBETOTAG_SHOW_STYLES = 'layout-subscribetotag-show-styles';
    public final const COMPONENT_LAYOUT_SUBSCRIBETOTAG_HIDE_STYLES = 'layout-subscribetotag-hide-styles';
    public final const COMPONENT_LAYOUT_UNSUBSCRIBEFROMTAG_SHOW_STYLES = 'layout-unsubscribefromtag-show-styles';
    public final const COMPONENT_LAYOUT_UNSUBSCRIBEFROMTAG_HIDE_STYLES = 'layout-unsubscribefromtag-hide-styles';
    public final const COMPONENT_LAYOUT_UPVOTEPOST_SHOW_STYLES = 'layout-upvoteposts-show-styles';
    public final const COMPONENT_LAYOUT_UPVOTEPOST_HIDE_STYLES = 'layout-upvoteposts-hide-styles';
    public final const COMPONENT_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES = 'layout-undoupvoteposts-show-styles';
    public final const COMPONENT_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES = 'layout-undoupvoteposts-hide-styles';
    public final const COMPONENT_LAYOUT_DOWNVOTEPOST_SHOW_STYLES = 'layout-downvoteposts-show-styles';
    public final const COMPONENT_LAYOUT_DOWNVOTEPOST_HIDE_STYLES = 'layout-downvoteposts-hide-styles';
    public final const COMPONENT_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES = 'layout-undodownvoteposts-show-styles';
    public final const COMPONENT_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES = 'layout-undodownvoteposts-hide-styles';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FOLLOWUSER_SHOW_STYLES],
            [self::class, self::COMPONENT_LAYOUT_FOLLOWUSER_HIDE_STYLES],
            [self::class, self::COMPONENT_LAYOUT_UNFOLLOWUSER_SHOW_STYLES],
            [self::class, self::COMPONENT_LAYOUT_UNFOLLOWUSER_HIDE_STYLES],
            [self::class, self::COMPONENT_LAYOUT_RECOMMENDPOST_SHOW_STYLES],
            [self::class, self::COMPONENT_LAYOUT_RECOMMENDPOST_HIDE_STYLES],
            [self::class, self::COMPONENT_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES],
            [self::class, self::COMPONENT_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES],
            [self::class, self::COMPONENT_LAYOUT_SUBSCRIBETOTAG_SHOW_STYLES],
            [self::class, self::COMPONENT_LAYOUT_SUBSCRIBETOTAG_HIDE_STYLES],
            [self::class, self::COMPONENT_LAYOUT_UNSUBSCRIBEFROMTAG_SHOW_STYLES],
            [self::class, self::COMPONENT_LAYOUT_UNSUBSCRIBEFROMTAG_HIDE_STYLES],
            [self::class, self::COMPONENT_LAYOUT_UPVOTEPOST_SHOW_STYLES],
            [self::class, self::COMPONENT_LAYOUT_UPVOTEPOST_HIDE_STYLES],
            [self::class, self::COMPONENT_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES],
            [self::class, self::COMPONENT_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES],
            [self::class, self::COMPONENT_LAYOUT_DOWNVOTEPOST_SHOW_STYLES],
            [self::class, self::COMPONENT_LAYOUT_DOWNVOTEPOST_HIDE_STYLES],
            [self::class, self::COMPONENT_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES],
            [self::class, self::COMPONENT_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES],
        );
    }

    public function getElemTarget(array $component, array &$props)
    {
        $targets = array(
            self::COMPONENT_LAYOUT_FOLLOWUSER_SHOW_STYLES => GD_CLASS_FOLLOWUSER,
            self::COMPONENT_LAYOUT_UNFOLLOWUSER_SHOW_STYLES => GD_CLASS_UNFOLLOWUSER,
            self::COMPONENT_LAYOUT_RECOMMENDPOST_SHOW_STYLES => GD_CLASS_RECOMMENDPOST,
            self::COMPONENT_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES => GD_CLASS_UNRECOMMENDPOST,
            self::COMPONENT_LAYOUT_SUBSCRIBETOTAG_SHOW_STYLES => GD_CLASS_SUBSCRIBETOTAG,
            self::COMPONENT_LAYOUT_UNSUBSCRIBEFROMTAG_SHOW_STYLES => GD_CLASS_UNSUBSCRIBEFROMTAG,
            self::COMPONENT_LAYOUT_UPVOTEPOST_SHOW_STYLES => GD_CLASS_UPVOTEPOST,
            self::COMPONENT_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES => GD_CLASS_UNDOUPVOTEPOST,
            self::COMPONENT_LAYOUT_DOWNVOTEPOST_SHOW_STYLES => GD_CLASS_DOWNVOTEPOST,
            self::COMPONENT_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES => GD_CLASS_UNDODOWNVOTEPOST,
            self::COMPONENT_LAYOUT_FOLLOWUSER_HIDE_STYLES => GD_CLASS_FOLLOWUSER,
            self::COMPONENT_LAYOUT_UNFOLLOWUSER_HIDE_STYLES => GD_CLASS_UNFOLLOWUSER,
            self::COMPONENT_LAYOUT_RECOMMENDPOST_HIDE_STYLES => GD_CLASS_RECOMMENDPOST,
            self::COMPONENT_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES => GD_CLASS_UNRECOMMENDPOST,
            self::COMPONENT_LAYOUT_SUBSCRIBETOTAG_HIDE_STYLES => GD_CLASS_SUBSCRIBETOTAG,
            self::COMPONENT_LAYOUT_UNSUBSCRIBEFROMTAG_HIDE_STYLES => GD_CLASS_UNSUBSCRIBEFROMTAG,
            self::COMPONENT_LAYOUT_UPVOTEPOST_HIDE_STYLES => GD_CLASS_UPVOTEPOST,
            self::COMPONENT_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES => GD_CLASS_UNDOUPVOTEPOST,
            self::COMPONENT_LAYOUT_DOWNVOTEPOST_HIDE_STYLES => GD_CLASS_DOWNVOTEPOST,
            self::COMPONENT_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES => GD_CLASS_UNDODOWNVOTEPOST,
        );
        if ($target = $targets[$component[1]] ?? null) {
            return '.'.$target;
        }

        return parent::getElemTarget($component, $props);
    }

    public function getElemStyles(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FOLLOWUSER_SHOW_STYLES:
            case self::COMPONENT_LAYOUT_UNFOLLOWUSER_SHOW_STYLES:
            case self::COMPONENT_LAYOUT_RECOMMENDPOST_SHOW_STYLES:
            case self::COMPONENT_LAYOUT_UNRECOMMENDPOST_SHOW_STYLES:
            case self::COMPONENT_LAYOUT_SUBSCRIBETOTAG_SHOW_STYLES:
            case self::COMPONENT_LAYOUT_UNSUBSCRIBEFROMTAG_SHOW_STYLES:
            case self::COMPONENT_LAYOUT_UPVOTEPOST_SHOW_STYLES:
            case self::COMPONENT_LAYOUT_UNDOUPVOTEPOST_SHOW_STYLES:
            case self::COMPONENT_LAYOUT_DOWNVOTEPOST_SHOW_STYLES:
            case self::COMPONENT_LAYOUT_UNDODOWNVOTEPOST_SHOW_STYLES:
                return array(
                    'display' => 'block'
                );

            case self::COMPONENT_LAYOUT_FOLLOWUSER_HIDE_STYLES:
            case self::COMPONENT_LAYOUT_UNFOLLOWUSER_HIDE_STYLES:
            case self::COMPONENT_LAYOUT_RECOMMENDPOST_HIDE_STYLES:
            case self::COMPONENT_LAYOUT_UNRECOMMENDPOST_HIDE_STYLES:
            case self::COMPONENT_LAYOUT_SUBSCRIBETOTAG_HIDE_STYLES:
            case self::COMPONENT_LAYOUT_UNSUBSCRIBEFROMTAG_HIDE_STYLES:
            case self::COMPONENT_LAYOUT_UPVOTEPOST_HIDE_STYLES:
            case self::COMPONENT_LAYOUT_UNDOUPVOTEPOST_HIDE_STYLES:
            case self::COMPONENT_LAYOUT_DOWNVOTEPOST_HIDE_STYLES:
            case self::COMPONENT_LAYOUT_UNDODOWNVOTEPOST_HIDE_STYLES:
                return array(
                    'display' => 'none'
                );
        }

        return parent::getElemStyles($component, $props);
    }
}




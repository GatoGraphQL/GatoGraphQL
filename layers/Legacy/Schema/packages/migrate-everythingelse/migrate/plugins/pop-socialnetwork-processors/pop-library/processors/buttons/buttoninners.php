<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_FOLLOWUSER_PREVIEW = 'buttoninner-followuser-preview';
    public final const MODULE_BUTTONINNER_FOLLOWUSER_FULL = 'viewcomponentuttoninner-sidebar-followuser-full';
    public final const MODULE_BUTTONINNER_UNFOLLOWUSER_PREVIEW = 'buttoninner-unfollowuser-preview';
    public final const MODULE_BUTTONINNER_UNFOLLOWUSER_FULL = 'buttoninner-sidebar-unfollowuser-full';
    public final const MODULE_BUTTONINNER_RECOMMENDPOST_PREVIEW = 'buttoninner-recommendpost-preview';
    public final const MODULE_BUTTONINNER_RECOMMENDPOST_FULL = 'buttoninner-sidebar-recommendpost-full';
    public final const MODULE_BUTTONINNER_UNRECOMMENDPOST_PREVIEW = 'buttoninner-unrecommendpost-preview';
    public final const MODULE_BUTTONINNER_UNRECOMMENDPOST_FULL = 'buttoninner-sidebar-unrecommendpost-full';
    public final const MODULE_BUTTONINNER_SUBSCRIBETOTAG_PREVIEW = 'buttoninner-subscribetotag-preview';
    public final const MODULE_BUTTONINNER_SUBSCRIBETOTAG_FULL = 'buttoninner-sidebar-subscribetotag-full';
    public final const MODULE_BUTTONINNER_UNSUBSCRIBEFROMTAG_PREVIEW = 'buttoninner-unsubscribefromtag-preview';
    public final const MODULE_BUTTONINNER_UNSUBSCRIBEFROMTAG_FULL = 'buttoninner-sidebar-unsubscribefromtag-full';
    public final const MODULE_BUTTONINNER_UPVOTEPOST_PREVIEW = 'buttoninner-upvotepost-preview';
    public final const MODULE_BUTTONINNER_UPVOTEPOST_FULL = 'viewcomponentuttoninner-sidebar-upvotepost-full';
    public final const MODULE_BUTTONINNER_UNDOUPVOTEPOST_PREVIEW = 'buttoninner-undoupvotepost-preview';
    public final const MODULE_BUTTONINNER_UNDOUPVOTEPOST_FULL = 'buttoninner-sidebar-undoupvotepost-full';
    public final const MODULE_BUTTONINNER_DOWNVOTEPOST_PREVIEW = 'buttoninner-downvotepost-preview';
    public final const MODULE_BUTTONINNER_DOWNVOTEPOST_FULL = 'buttoninner-sidebar-downvotepost-full';
    public final const MODULE_BUTTONINNER_UNDODOWNVOTEPOST_PREVIEW = 'buttoninner-undodownvotepost-preview';
    public final const MODULE_BUTTONINNER_UNDODOWNVOTEPOST_FULL = 'buttoninner-sidebar-undodownvotepost-full';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_FOLLOWUSER_PREVIEW],
            [self::class, self::MODULE_BUTTONINNER_FOLLOWUSER_FULL],
            [self::class, self::MODULE_BUTTONINNER_UNFOLLOWUSER_PREVIEW],
            [self::class, self::MODULE_BUTTONINNER_UNFOLLOWUSER_FULL],
            [self::class, self::MODULE_BUTTONINNER_RECOMMENDPOST_PREVIEW],
            [self::class, self::MODULE_BUTTONINNER_RECOMMENDPOST_FULL],
            [self::class, self::MODULE_BUTTONINNER_UNRECOMMENDPOST_PREVIEW],
            [self::class, self::MODULE_BUTTONINNER_UNRECOMMENDPOST_FULL],
            [self::class, self::MODULE_BUTTONINNER_SUBSCRIBETOTAG_PREVIEW],
            [self::class, self::MODULE_BUTTONINNER_SUBSCRIBETOTAG_FULL],
            [self::class, self::MODULE_BUTTONINNER_UNSUBSCRIBEFROMTAG_PREVIEW],
            [self::class, self::MODULE_BUTTONINNER_UNSUBSCRIBEFROMTAG_FULL],
            [self::class, self::MODULE_BUTTONINNER_UPVOTEPOST_PREVIEW],
            [self::class, self::MODULE_BUTTONINNER_UPVOTEPOST_FULL],
            [self::class, self::MODULE_BUTTONINNER_UNDOUPVOTEPOST_PREVIEW],
            [self::class, self::MODULE_BUTTONINNER_UNDOUPVOTEPOST_FULL],
            [self::class, self::MODULE_BUTTONINNER_DOWNVOTEPOST_PREVIEW],
            [self::class, self::MODULE_BUTTONINNER_DOWNVOTEPOST_FULL],
            [self::class, self::MODULE_BUTTONINNER_UNDODOWNVOTEPOST_PREVIEW],
            [self::class, self::MODULE_BUTTONINNER_UNDODOWNVOTEPOST_FULL],
        );
    }

    public function getTag(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONINNER_FOLLOWUSER_FULL:
            case self::MODULE_BUTTONINNER_UNFOLLOWUSER_FULL:
            case self::MODULE_BUTTONINNER_RECOMMENDPOST_FULL:
            case self::MODULE_BUTTONINNER_UNRECOMMENDPOST_FULL:
            case self::MODULE_BUTTONINNER_SUBSCRIBETOTAG_FULL:
            case self::MODULE_BUTTONINNER_UNSUBSCRIBEFROMTAG_FULL:
            case self::MODULE_BUTTONINNER_UPVOTEPOST_FULL:
            case self::MODULE_BUTTONINNER_UNDOUPVOTEPOST_FULL:
            case self::MODULE_BUTTONINNER_DOWNVOTEPOST_FULL:
            case self::MODULE_BUTTONINNER_UNDODOWNVOTEPOST_FULL:
                return 'h4';
        }

        return parent::getTag($module);
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONINNER_FOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTONINNER_FOLLOWUSER_FULL:
            case self::MODULE_BUTTONINNER_UNFOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTONINNER_UNFOLLOWUSER_FULL:
            case self::MODULE_BUTTONINNER_SUBSCRIBETOTAG_PREVIEW:
            case self::MODULE_BUTTONINNER_SUBSCRIBETOTAG_FULL:
            case self::MODULE_BUTTONINNER_UNSUBSCRIBEFROMTAG_PREVIEW:
            case self::MODULE_BUTTONINNER_UNSUBSCRIBEFROMTAG_FULL:
                return 'fa-fw fa-hand-o-right';

            case self::MODULE_BUTTONINNER_RECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_RECOMMENDPOST_FULL:
            case self::MODULE_BUTTONINNER_UNRECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_UNRECOMMENDPOST_FULL:
                return 'fa-fw fa-thumbs-o-up';

            case self::MODULE_BUTTONINNER_UPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_UPVOTEPOST_FULL:
            case self::MODULE_BUTTONINNER_UNDOUPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_UNDOUPVOTEPOST_FULL:
                return 'fa-fw fa-thumbs-up';

            case self::MODULE_BUTTONINNER_DOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_DOWNVOTEPOST_FULL:
            case self::MODULE_BUTTONINNER_UNDODOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_UNDODOWNVOTEPOST_FULL:
                return 'fa-fw fa-thumbs-down';
        }

        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONINNER_FOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTONINNER_FOLLOWUSER_FULL:
                return TranslationAPIFacade::getInstance()->__('Follow', 'pop-coreprocessors');

            case self::MODULE_BUTTONINNER_UNFOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTONINNER_UNFOLLOWUSER_FULL:
                return TranslationAPIFacade::getInstance()->__('Following', 'pop-coreprocessors');

            case self::MODULE_BUTTONINNER_RECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_RECOMMENDPOST_FULL:
            case self::MODULE_BUTTONINNER_UNRECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_UNRECOMMENDPOST_FULL:
                return TranslationAPIFacade::getInstance()->__('Recommend', 'pop-coreprocessors');

            case self::MODULE_BUTTONINNER_SUBSCRIBETOTAG_PREVIEW:
            case self::MODULE_BUTTONINNER_SUBSCRIBETOTAG_FULL:
            case self::MODULE_BUTTONINNER_UNSUBSCRIBEFROMTAG_PREVIEW:
            case self::MODULE_BUTTONINNER_UNSUBSCRIBEFROMTAG_FULL:
                return TranslationAPIFacade::getInstance()->__('Subscribe', 'pop-coreprocessors');
        }

        return parent::getBtnTitle($module);
    }

    public function getBtntitleClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONINNER_FOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTONINNER_FOLLOWUSER_FULL:
            case self::MODULE_BUTTONINNER_UNFOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTONINNER_UNFOLLOWUSER_FULL:
                return 'visible';
        }
        
        return parent::getBtntitleClass($module, $props);
    }

    public function getTextField(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONINNER_RECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_RECOMMENDPOST_FULL:
                return 'recommendPostCount';

            case self::MODULE_BUTTONINNER_UNRECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_UNRECOMMENDPOST_FULL:
                return 'recommendPostCountPlus1';

            case self::MODULE_BUTTONINNER_UPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_UPVOTEPOST_FULL:
                return 'upvotePostCount';

            case self::MODULE_BUTTONINNER_UNDOUPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_UNDOUPVOTEPOST_FULL:
                return 'upvotePostCountPlus1';

            case self::MODULE_BUTTONINNER_DOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_DOWNVOTEPOST_FULL:
                return 'downvotePostCount';

            case self::MODULE_BUTTONINNER_UNDODOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTONINNER_UNDODOWNVOTEPOST_FULL:
                return 'downvotePostCountPlus1';
        }
        
        return parent::getTextField($module, $props);
    }
}



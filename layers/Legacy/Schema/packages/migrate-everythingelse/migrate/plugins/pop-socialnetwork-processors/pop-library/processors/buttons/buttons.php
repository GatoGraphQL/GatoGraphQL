<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_FunctionButtons extends PoP_Module_Processor_ButtonsBase
{
    public final const MODULE_BUTTON_FOLLOWUSER_PREVIEW = 'button-followuser-preview';
    public final const MODULE_BUTTON_FOLLOWUSER_FULL = 'button-sidebar-followuser-full';
    public final const MODULE_BUTTON_UNFOLLOWUSER_PREVIEW = 'button-unfollowuser-preview';
    public final const MODULE_BUTTON_UNFOLLOWUSER_FULL = 'button-sidebar-unfollowuser-full';
    public final const MODULE_BUTTON_RECOMMENDPOST_FULL = 'button-recommendpost-full';
    public final const MODULE_BUTTON_RECOMMENDPOST_PREVIEW = 'button-recommendpost-preview';
    public final const MODULE_BUTTON_UNRECOMMENDPOST_FULL = 'button-unrecommendpost-full';
    public final const MODULE_BUTTON_UNRECOMMENDPOST_PREVIEW = 'button-unrecommendpost-preview';
    public final const MODULE_BUTTON_SUBSCRIBETOTAG_FULL = 'button-subscribetotag-full';
    public final const MODULE_BUTTON_SUBSCRIBETOTAG_PREVIEW = 'button-subscribetotag-preview';
    public final const MODULE_BUTTON_UNSUBSCRIBEFROMTAG_FULL = 'button-unsubscribefromtag-full';
    public final const MODULE_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW = 'button-unsubscribefromtag-preview';
    public final const MODULE_BUTTON_UPVOTEPOST_PREVIEW = 'button-upvotepost-preview';
    public final const MODULE_BUTTON_UPVOTEPOST_FULL = 'button-sidebar-upvotepost-full';
    public final const MODULE_BUTTON_UNDOUPVOTEPOST_PREVIEW = 'button-undoupvotepost-preview';
    public final const MODULE_BUTTON_UNDOUPVOTEPOST_FULL = 'button-sidebar-undoupvotepost-full';
    public final const MODULE_BUTTON_DOWNVOTEPOST_FULL = 'button-downvotepost-full';
    public final const MODULE_BUTTON_DOWNVOTEPOST_PREVIEW = 'button-downvotepost-preview';
    public final const MODULE_BUTTON_UNDODOWNVOTEPOST_FULL = 'button-undodownvotepost-full';
    public final const MODULE_BUTTON_UNDODOWNVOTEPOST_PREVIEW = 'button-undodownvotepost-preview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_FOLLOWUSER_PREVIEW],
            [self::class, self::MODULE_BUTTON_FOLLOWUSER_FULL],
            [self::class, self::MODULE_BUTTON_UNFOLLOWUSER_PREVIEW],
            [self::class, self::MODULE_BUTTON_UNFOLLOWUSER_FULL],
            [self::class, self::MODULE_BUTTON_RECOMMENDPOST_FULL],
            [self::class, self::MODULE_BUTTON_RECOMMENDPOST_PREVIEW],
            [self::class, self::MODULE_BUTTON_UNRECOMMENDPOST_FULL],
            [self::class, self::MODULE_BUTTON_UNRECOMMENDPOST_PREVIEW],
            [self::class, self::MODULE_BUTTON_SUBSCRIBETOTAG_FULL],
            [self::class, self::MODULE_BUTTON_SUBSCRIBETOTAG_PREVIEW],
            [self::class, self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_FULL],
            [self::class, self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW],
            [self::class, self::MODULE_BUTTON_UPVOTEPOST_PREVIEW],
            [self::class, self::MODULE_BUTTON_UPVOTEPOST_FULL],
            [self::class, self::MODULE_BUTTON_UNDOUPVOTEPOST_PREVIEW],
            [self::class, self::MODULE_BUTTON_UNDOUPVOTEPOST_FULL],
            [self::class, self::MODULE_BUTTON_DOWNVOTEPOST_FULL],
            [self::class, self::MODULE_BUTTON_DOWNVOTEPOST_PREVIEW],
            [self::class, self::MODULE_BUTTON_UNDODOWNVOTEPOST_FULL],
            [self::class, self::MODULE_BUTTON_UNDODOWNVOTEPOST_PREVIEW],
        );
    }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_FOLLOWUSER_PREVIEW => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_FOLLOWUSER_PREVIEW],
            self::MODULE_BUTTON_FOLLOWUSER_FULL => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_FOLLOWUSER_FULL],
            self::MODULE_BUTTON_UNFOLLOWUSER_PREVIEW => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_UNFOLLOWUSER_PREVIEW],
            self::MODULE_BUTTON_UNFOLLOWUSER_FULL => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_UNFOLLOWUSER_FULL],
            self::MODULE_BUTTON_RECOMMENDPOST_FULL => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_RECOMMENDPOST_FULL],
            self::MODULE_BUTTON_RECOMMENDPOST_PREVIEW => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_RECOMMENDPOST_PREVIEW],
            self::MODULE_BUTTON_UNRECOMMENDPOST_FULL => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_UNRECOMMENDPOST_FULL],
            self::MODULE_BUTTON_UNRECOMMENDPOST_PREVIEW => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_UNRECOMMENDPOST_PREVIEW],
            self::MODULE_BUTTON_SUBSCRIBETOTAG_FULL => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_SUBSCRIBETOTAG_FULL],
            self::MODULE_BUTTON_SUBSCRIBETOTAG_PREVIEW => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_SUBSCRIBETOTAG_PREVIEW],
            self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_FULL => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_UNSUBSCRIBEFROMTAG_FULL],
            self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_UNSUBSCRIBEFROMTAG_PREVIEW],
            self::MODULE_BUTTON_UPVOTEPOST_PREVIEW => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_UPVOTEPOST_PREVIEW],
            self::MODULE_BUTTON_UPVOTEPOST_FULL => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_UPVOTEPOST_FULL],
            self::MODULE_BUTTON_UNDOUPVOTEPOST_PREVIEW => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_UNDOUPVOTEPOST_PREVIEW],
            self::MODULE_BUTTON_UNDOUPVOTEPOST_FULL => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_UNDOUPVOTEPOST_FULL],
            self::MODULE_BUTTON_DOWNVOTEPOST_FULL => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_DOWNVOTEPOST_FULL],
            self::MODULE_BUTTON_DOWNVOTEPOST_PREVIEW => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_DOWNVOTEPOST_PREVIEW],
            self::MODULE_BUTTON_UNDODOWNVOTEPOST_FULL => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_UNDODOWNVOTEPOST_FULL],
            self::MODULE_BUTTON_UNDODOWNVOTEPOST_PREVIEW => [PoP_SocialNetwork_Module_Processor_ButtonInners::class, PoP_SocialNetwork_Module_Processor_ButtonInners::MODULE_BUTTONINNER_UNDODOWNVOTEPOST_PREVIEW],
        );
        if ($buttoninner = $buttoninners[$componentVariation[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }

    public function getUrlField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_FOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTON_FOLLOWUSER_FULL:
                return 'followUserURL';

            case self::MODULE_BUTTON_UNFOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTON_UNFOLLOWUSER_FULL:
                return 'unfollowUserURL';

            case self::MODULE_BUTTON_RECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTON_RECOMMENDPOST_FULL:
                return 'recommendPostURL';

            case self::MODULE_BUTTON_UNRECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTON_UNRECOMMENDPOST_FULL:
                return 'unrecommendPostURL';

            case self::MODULE_BUTTON_SUBSCRIBETOTAG_PREVIEW:
            case self::MODULE_BUTTON_SUBSCRIBETOTAG_FULL:
                return 'subscribeToTagURL';

            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW:
            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_FULL:
                return 'unsubscribeFromTagURL';

            case self::MODULE_BUTTON_UPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UPVOTEPOST_FULL:
                return 'upvotePostURL';

            case self::MODULE_BUTTON_UNDOUPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDOUPVOTEPOST_FULL:
                return 'undoUpvotePostURL';

            case self::MODULE_BUTTON_DOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_DOWNVOTEPOST_FULL:
                return 'downvotePostURL';

            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_FULL:
                return 'undoDownvotePostURL';
        }

        return parent::getUrlField($componentVariation);
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_FOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTON_FOLLOWUSER_FULL:
                return TranslationAPIFacade::getInstance()->__('Follow', 'pop-coreprocessors');

            case self::MODULE_BUTTON_UNFOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTON_UNFOLLOWUSER_FULL:
                return TranslationAPIFacade::getInstance()->__('Following', 'pop-coreprocessors');

            case self::MODULE_BUTTON_RECOMMENDPOST_FULL:
            case self::MODULE_BUTTON_RECOMMENDPOST_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('Recommend', 'pop-coreprocessors');

            case self::MODULE_BUTTON_UNRECOMMENDPOST_FULL:
            case self::MODULE_BUTTON_UNRECOMMENDPOST_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('Recommended', 'pop-coreprocessors');

            case self::MODULE_BUTTON_SUBSCRIBETOTAG_FULL:
            case self::MODULE_BUTTON_SUBSCRIBETOTAG_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('Subscribe', 'pop-coreprocessors');

            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_FULL:
            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW:
                return TranslationAPIFacade::getInstance()->__('Subscribed', 'pop-coreprocessors');

            case self::MODULE_BUTTON_UPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UPVOTEPOST_FULL:
                return TranslationAPIFacade::getInstance()->__('Up-vote', 'pop-coreprocessors');

            case self::MODULE_BUTTON_UNDOUPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDOUPVOTEPOST_FULL:
                return TranslationAPIFacade::getInstance()->__('Up-voted', 'pop-coreprocessors');

            case self::MODULE_BUTTON_DOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_DOWNVOTEPOST_FULL:
                return TranslationAPIFacade::getInstance()->__('Down-vote', 'pop-coreprocessors');

            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_FULL:
                return TranslationAPIFacade::getInstance()->__('Down-voted', 'pop-coreprocessors');
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_FOLLOWUSER_FULL:
            case self::MODULE_BUTTON_UNFOLLOWUSER_FULL:
            case self::MODULE_BUTTON_RECOMMENDPOST_FULL:
            case self::MODULE_BUTTON_UNRECOMMENDPOST_FULL:
            case self::MODULE_BUTTON_SUBSCRIBETOTAG_FULL:
            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_FULL:
            case self::MODULE_BUTTON_UPVOTEPOST_FULL:
            case self::MODULE_BUTTON_UNDOUPVOTEPOST_FULL:
            case self::MODULE_BUTTON_DOWNVOTEPOST_FULL:
            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_FULL:
                $ret .= ' btn btn-sm btn-success btn-block btn-important';
                break;

            case self::MODULE_BUTTON_FOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTON_UNFOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTON_RECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTON_UNRECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTON_SUBSCRIBETOTAG_PREVIEW:
            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW:
            case self::MODULE_BUTTON_UPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDOUPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_DOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_PREVIEW:
                $ret .= ' btn btn-compact btn-link';
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_FOLLOWUSER_FULL:
            case self::MODULE_BUTTON_UNFOLLOWUSER_FULL:
                $ret .= ' pop-hidden-print';
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_FOLLOWUSER_FULL:
            case self::MODULE_BUTTON_FOLLOWUSER_PREVIEW:
                $ret .= ' '.GD_CLASS_FOLLOWUSER;
                break;

            case self::MODULE_BUTTON_UNFOLLOWUSER_FULL:
            case self::MODULE_BUTTON_UNFOLLOWUSER_PREVIEW:
                $ret .= ' '.GD_CLASS_UNFOLLOWUSER;
                break;

            case self::MODULE_BUTTON_RECOMMENDPOST_FULL:
            case self::MODULE_BUTTON_RECOMMENDPOST_PREVIEW:
                $ret .= ' '.GD_CLASS_RECOMMENDPOST;
                break;

            case self::MODULE_BUTTON_UNRECOMMENDPOST_FULL:
            case self::MODULE_BUTTON_UNRECOMMENDPOST_PREVIEW:
                $ret .= ' '.GD_CLASS_UNRECOMMENDPOST;
                break;

            case self::MODULE_BUTTON_SUBSCRIBETOTAG_FULL:
            case self::MODULE_BUTTON_SUBSCRIBETOTAG_PREVIEW:
                $ret .= ' '.GD_CLASS_SUBSCRIBETOTAG;
                break;

            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_FULL:
            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW:
                $ret .= ' '.GD_CLASS_UNSUBSCRIBEFROMTAG;
                break;

            case self::MODULE_BUTTON_UPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UPVOTEPOST_FULL:
                $ret .= ' '.GD_CLASS_UPVOTEPOST;
                break;

            case self::MODULE_BUTTON_UNDOUPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDOUPVOTEPOST_FULL:
                $ret .= ' '.GD_CLASS_UNDOUPVOTEPOST;
                break;

            case self::MODULE_BUTTON_DOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_DOWNVOTEPOST_FULL:
                $ret .= ' '.GD_CLASS_DOWNVOTEPOST;
                break;

            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_FULL:
                $ret .= ' '.GD_CLASS_UNDODOWNVOTEPOST;
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_FOLLOWUSER_FULL:
            case self::MODULE_BUTTON_FOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTON_UNFOLLOWUSER_FULL:
            case self::MODULE_BUTTON_UNFOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTON_RECOMMENDPOST_FULL:
            case self::MODULE_BUTTON_RECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTON_UNRECOMMENDPOST_FULL:
            case self::MODULE_BUTTON_UNRECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTON_SUBSCRIBETOTAG_FULL:
            case self::MODULE_BUTTON_SUBSCRIBETOTAG_PREVIEW:
            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_FULL:
            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW:
            case self::MODULE_BUTTON_UPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UPVOTEPOST_FULL:
            case self::MODULE_BUTTON_UNDOUPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDOUPVOTEPOST_FULL:
            case self::MODULE_BUTTON_DOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_DOWNVOTEPOST_FULL:
            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_FULL:
                $ret .= ' pop-functionbutton';
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_FOLLOWUSER_FULL:
            case self::MODULE_BUTTON_FOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTON_RECOMMENDPOST_FULL:
            case self::MODULE_BUTTON_RECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTON_SUBSCRIBETOTAG_FULL:
            case self::MODULE_BUTTON_SUBSCRIBETOTAG_PREVIEW:
            case self::MODULE_BUTTON_UPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UPVOTEPOST_FULL:
            case self::MODULE_BUTTON_DOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_DOWNVOTEPOST_FULL:
                $ret .= ' pop-functionaction';
                break;

            case self::MODULE_BUTTON_UNFOLLOWUSER_FULL:
            case self::MODULE_BUTTON_UNFOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTON_UNRECOMMENDPOST_FULL:
            case self::MODULE_BUTTON_UNRECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_FULL:
            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW:
            case self::MODULE_BUTTON_UNDOUPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDOUPVOTEPOST_FULL:
            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_FULL:
                $ret .= ' pop-functionunaction';
                break;
        }

        // Make the classes 'active' as to make them appear as they've been clicked from the previous state
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_UNFOLLOWUSER_FULL:
            case self::MODULE_BUTTON_UNFOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTON_UNRECOMMENDPOST_FULL:
            case self::MODULE_BUTTON_UNRECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_FULL:
            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW:
            case self::MODULE_BUTTON_UNDOUPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDOUPVOTEPOST_FULL:
            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_FULL:
                $ret .= ' active';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_FOLLOWUSER_FULL:
            case self::MODULE_BUTTON_FOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTON_RECOMMENDPOST_FULL:
            case self::MODULE_BUTTON_RECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTON_SUBSCRIBETOTAG_FULL:
            case self::MODULE_BUTTON_SUBSCRIBETOTAG_PREVIEW:
            case self::MODULE_BUTTON_UPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UPVOTEPOST_FULL:
            case self::MODULE_BUTTON_DOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_DOWNVOTEPOST_FULL:
            case self::MODULE_BUTTON_UNFOLLOWUSER_FULL:
            case self::MODULE_BUTTON_UNFOLLOWUSER_PREVIEW:
            case self::MODULE_BUTTON_UNRECOMMENDPOST_FULL:
            case self::MODULE_BUTTON_UNRECOMMENDPOST_PREVIEW:
            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_FULL:
            case self::MODULE_BUTTON_UNSUBSCRIBEFROMTAG_PREVIEW:
            case self::MODULE_BUTTON_UNDOUPVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDOUPVOTEPOST_FULL:
            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_PREVIEW:
            case self::MODULE_BUTTON_UNDODOWNVOTEPOST_FULL:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($componentVariation, $props, 'resourceloader', 'functionbutton');

                // Tell the Search engines to not follow the link
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'rel' => 'nofollow',
                    )
                );
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



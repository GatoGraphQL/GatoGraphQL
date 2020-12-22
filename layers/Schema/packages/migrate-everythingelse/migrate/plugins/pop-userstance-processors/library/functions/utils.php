<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_UserStanceProcessors_Utils
{
    public static function getLatestvotesTitle()
    {

        // Allow TPPDebate to override this title
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_UserStanceProcessors_Utils:latestvotes_title',
            TranslationAPIFacade::getInstance()->__('Latest votes', 'pop-userstance-processors')
        );
    }

    public static function getWhatisyourvoteTitle($format = '')
    {
        if ($format == 'lc') {
            $title = TranslationAPIFacade::getInstance()->__('what is your vote?', 'pop-userstance-processors');
        } else {
            $title = TranslationAPIFacade::getInstance()->__('What is your vote?', 'pop-userstance-processors');
        }

        // Allow TPPDebate to override this title
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_UserStanceProcessors_Utils:whatisyourvote_title',
            $title,
            $format
        );
    }
}

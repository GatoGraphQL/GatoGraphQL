<?php
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class PoPTheme_Wassup_AE_NewsletterSpecialSinglePost extends PoPTheme_Wassup_AE_NewsletterRecipientsBase
{
    public function getRoute()
    {
        return POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL;
    }

    protected function getSubject()
    {
        if (isset($_REQUEST[\PoPSchema\Posts\Constants\InputNames::POST_ID])) {
            // The post id is passed through param pid
            $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
            return $customPostTypeAPI->getTitle($_REQUEST[\PoPSchema\Posts\Constants\InputNames::POST_ID]);
        }
        return '';
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_AE_NewsletterSpecialSinglePost();

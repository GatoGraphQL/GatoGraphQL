<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-coreprocessors/js/helpers.handlebars.js
*/
class PoP_ServerSide_FeedbackMessageHelperCallers
{
    public static function formatFeedbackMessage($message, $options)
    {
        global $pop_serverside_feedbackmessagehelpers;
        return $pop_serverside_feedbackmessagehelpers->formatFeedbackMessage($message, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_FeedbackMessageHelperCallers::class);

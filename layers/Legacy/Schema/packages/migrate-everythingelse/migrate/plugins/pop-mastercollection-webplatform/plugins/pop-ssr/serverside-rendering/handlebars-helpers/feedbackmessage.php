<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_FeedbackMessageHelpers
{
    public function formatFeedbackMessage($message, $options)
    {

        // ------------------------------------------------------
        // Comment Leo: Not needed in PHP => Commented out
        // ------------------------------------------------------
        // Please notice: this function will NEVER be executed, because if the block is multidomain,
        // then it will always fetch data lazy-load, so the feedbackMessage will then never be printed on the server
        // $isMultiDomain = $options['hash']['is-multidomain'];
        // $domain = $options['hash']['domain'];
        // if ($isMultiDomain && $domain) {

        //     // If specified the domain, then add its name in the message, through a customizable format
        //     $websiteproperties = PoP_MultiDomain_Utils::getMultidomainWebsites();
        //     $name = $websiteproperties[$domain] ? $websiteproperties[$domain]['name'] : $domain;
        //     $message = sprintf(
        //         str_replace(array('{0}', '{1}'), array('%1$s', '%2$s'), GD_CONSTANT_FEEDBACKMSG_MULTIDOMAIN),
        //         $name,
        //         $message
        //     );
        // }

        return new LS($message);
    }
}

/**
 * Initialization
 */
global $pop_serverside_feedbackmessagehelpers;
$pop_serverside_feedbackmessagehelpers = new PoP_ServerSide_FeedbackMessageHelpers();

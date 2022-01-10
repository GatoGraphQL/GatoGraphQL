<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

define('POP_EMAIL_FOLLOWSUSER', 'followsuser');
define('POP_EMAIL_RECOMMENDSPOST', 'recommendspost');
define('POP_EMAIL_UPDOWNVOTEDPOST', 'updownvotedpost');

class PoP_SocialNetwork_EmailSender_Hooks
{
    public function __construct()
    {

        // Important: do not change the order of the hooks added below, because users receive only 1 email for each type (eg: added a post),
        // so if they suit 2 different hooks (eg: general preferences and user network preferences) then send it under the most specific one (eg: user network preferences)

        //----------------------------------------------------------------------
        // Functional emails
        //----------------------------------------------------------------------
        // User followed
        HooksAPIFacade::getInstance()->addAction('gd_followuser', array($this, 'followuser'), 10, 1);

        // Post recommended
        HooksAPIFacade::getInstance()->addAction('gd_recommendpost', array($this, 'recommendpost'), 10, 1);

        //----------------------------------------------------------------------
        // Email Notifications
        //----------------------------------------------------------------------
        // EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST:
        HooksAPIFacade::getInstance()->addAction('gd_recommendpost', array($this, 'emailnotificationsNetworkRecommendedpost'), 10, 1);
        // EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER:
        HooksAPIFacade::getInstance()->addAction('gd_followuser', array($this, 'emailnotificationsNetworkFolloweduser'), 10, 1);
        // EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST:
        HooksAPIFacade::getInstance()->addAction('gd_upvotepost', array($this, 'emailnotificationsNetworkUpvotedpost'), 10, 1);
        HooksAPIFacade::getInstance()->addAction('gd_downvotepost', array($this, 'emailnotificationsNetworkDownvotedpost'), 10, 1);
    }

    /**
     * Email Notifications
     */

    public function emailnotificationsNetworkFolloweduser($target_id)
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['current-user-id'];

        // Get the current user's network's users (followers + members of same communities)
        $networkusers = PoP_SocialNetwork_EmailUtils::getUserNetworkusers($user_id);
        if ($networkusers = array_diff($networkusers, PoP_EmailSender_SentEmailsManager::getSentemailUsers(POP_EMAIL_FOLLOWSUSER))) {
            // Keep only the users with the corresponding preference on
            if ($networkusers = PoP_UserPlatform_UserPreferencesUtils::getPreferenceonUsers(POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER, $networkusers)) {
                $userTypeAPI = UserTypeAPIFacade::getInstance();
                $emails = $names = array();
                foreach ($networkusers as $networkuser) {
                    $emails[] = $userTypeAPI->getUserEmail($networkuser);
                    $names[] = $userTypeAPI->getUserDisplayName($networkuser);
                }

                $user_url = $userTypeAPI->getUserURL($user_id);
                $user_name = $userTypeAPI->getUserDisplayName($user_id);
                $target_name = $userTypeAPI->getUserDisplayName($target_id);
                $subject = sprintf(
                    TranslationAPIFacade::getInstance()->__('%s is now following %s', 'pop-emailsender'),
                    $user_name,
                    $target_name
                );

                $content = sprintf(
                    TranslationAPIFacade::getInstance()->__('<p><a href="%s">%s</a> is now following:</p>', 'pop-emailsender'),
                    $user_url,
                    $user_name
                );
                $target_html = PoP_EmailTemplatesFactory::getInstance()->getUserhtml($target_id);
                $content .= $target_html;

                $footer = PoP_UserPlatform_EmailSenderUtils::getPreferencesFooter(TranslationAPIFacade::getInstance()->__('You are receiving this notification for belonging to the user’s network.', 'pop-emailsender'));
                $content .= $footer;

                PoP_EmailSender_Utils::sendemailToUsers($emails, $names, $subject, $content);

                // Add the users to the list of users who got an email sent to
                PoP_EmailSender_SentEmailsManager::addSentemailUsers(POP_EMAIL_FOLLOWSUSER, $networkusers);
            }
        }
    }
    public function emailnotificationsNetworkRecommendedpost($post_id)
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['current-user-id'];

        // Get the current user's network's users (followers + members of same communities)
        $networkusers = PoP_SocialNetwork_EmailUtils::getUserNetworkusers($user_id);
        if ($networkusers = array_diff($networkusers, PoP_EmailSender_SentEmailsManager::getSentemailUsers(POP_EMAIL_RECOMMENDSPOST))) {
            // Keep only the users with the corresponding preference on
            if ($networkusers = PoP_UserPlatform_UserPreferencesUtils::getPreferenceonUsers(POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST, $networkusers)) {
                $userTypeAPI = UserTypeAPIFacade::getInstance();
                $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
                $emails = $names = array();
                foreach ($networkusers as $networkuser) {
                    $emails[] = $userTypeAPI->getUserEmail($networkuser);
                    $names[] = $userTypeAPI->getUserDisplayName($networkuser);
                }

                $user_url = $userTypeAPI->getUserURL($user_id);
                $user_name = $userTypeAPI->getUserDisplayName($user_id);
                $post_title = $customPostTypeAPI->getTitle($post_id);
                $subject = sprintf(
                    TranslationAPIFacade::getInstance()->__('%s has recommended “%s”', 'pop-emailsender'),
                    $user_name,
                    $post_title
                );

                $content = sprintf(
                    TranslationAPIFacade::getInstance()->__('<p><a href="%s">%s</a> has recommended:</p>', 'pop-emailsender'),
                    $user_url,
                    $user_name
                );
                $post_html = PoP_EmailTemplatesFactory::getInstance()->getPosthtml($post_id);
                $content .= $post_html;

                $footer = PoP_UserPlatform_EmailSenderUtils::getPreferencesFooter(TranslationAPIFacade::getInstance()->__('You are receiving this notification for belonging to the user’s network.', 'pop-emailsender'));
                $content .= $footer;

                PoP_EmailSender_Utils::sendemailToUsers($emails, $names, $subject, $content);

                // Add the users to the list of users who got an email sent to
                PoP_EmailSender_SentEmailsManager::addSentemailUsers(POP_EMAIL_RECOMMENDSPOST, $networkusers);
            }
        }
    }

    public function emailnotificationsNetworkUpvotedpost($post_id)
    {
        $this->emailnotificationsNetworkUpdownvotedpost($post_id, true);
    }
    public function emailnotificationsNetworkDownvotedpost($post_id)
    {
        $this->emailnotificationsNetworkUpdownvotedpost($post_id, false);
    }
    protected function emailnotificationsNetworkUpdownvotedpost($post_id, $upvote)
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['current-user-id'];

        // Get the current user's network's users (followers + members of same communities)
        $networkusers = PoP_SocialNetwork_EmailUtils::getUserNetworkusers($user_id);
        if ($networkusers = array_diff($networkusers, PoP_EmailSender_SentEmailsManager::getSentemailUsers(POP_EMAIL_UPDOWNVOTEDPOST))) {
            // Keep only the users with the corresponding preference on
            if ($networkusers = PoP_UserPlatform_UserPreferencesUtils::getPreferenceonUsers(POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST, $networkusers)) {
                $userTypeAPI = UserTypeAPIFacade::getInstance();
                $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
                $emails = $names = array();
                foreach ($networkusers as $networkuser) {
                    $emails[] = $userTypeAPI->getUserEmail($networkuser);
                    $names[] = $userTypeAPI->getUserDisplayName($networkuser);
                }

                // No need to check if the post_status is "published", since it's been checked in the previous 2 functions (create/update)
                $post_html = PoP_EmailTemplatesFactory::getInstance()->getPosthtml($post_id);
                $post_name = gdGetPostname($post_id);
                $post_title = $customPostTypeAPI->getTitle($post_id);
                $footer = PoP_UserPlatform_EmailSenderUtils::getPreferencesFooter(TranslationAPIFacade::getInstance()->__('You are receiving this notification for belonging to the user’s network.', 'pop-emailsender'));

                $authors = gdGetPostauthors($post_id);
                $author = $authors[0];
                $author_name = $userTypeAPI->getUserDisplayName($author);
                $author_url = $userTypeAPI->getUserURL($author);
                $subject = sprintf(
                    $upvote ? TranslationAPIFacade::getInstance()->__('%s upvoted “%s”', 'pop-emailsender') : TranslationAPIFacade::getInstance()->__('%s downvoted “%s”', 'pop-emailsender'),
                    $author_name,
                    html_entity_decode($post_title)
                );
                $content = sprintf(
                    '<p>%s</p>',
                    sprintf(
                        $upvote ? TranslationAPIFacade::getInstance()->__('<a href="%s">%s</a> upvoted:', 'pop-emailsender') : TranslationAPIFacade::getInstance()->__('<a href="%s">%s</a> downvoted:', 'pop-emailsender'),
                        $author_url,
                        $author_name
                    )
                );
                $content .= $post_html;
                $content .= $footer;

                PoP_EmailSender_Utils::sendemailToUsers($emails, $names, $subject, $content);

                // Add the users to the list of users who got an email sent to
                PoP_EmailSender_SentEmailsManager::addSentemailUsers(POP_EMAIL_UPDOWNVOTEDPOST, $networkusers);
            }
        }
    }

    /**
     * Follow user
     */
    public function followuser($target_id)
    {
        if (!in_array($target_id, PoP_EmailSender_SentEmailsManager::getSentemailUsers(POP_EMAIL_FOLLOWSUSER))) {
            $userTypeAPI = UserTypeAPIFacade::getInstance();
            $vars = ApplicationState::getVars();
            $user_id = $vars['current-user-id'];
            $user_html = PoP_EmailTemplatesFactory::getInstance()->getUserhtml($user_id);

            $target_url = $userTypeAPI->getUserURL($target_id);
            $target_name = $userTypeAPI->getUserDisplayName($target_id);
            $subject = sprintf(TranslationAPIFacade::getInstance()->__('You have a new follower!', 'pop-emailsender'), $target_name);

            $content = sprintf(
                TranslationAPIFacade::getInstance()->__('<p>Congratulations! <a href="%s">You have a new follower</a>:</p>', 'pop-emailsender'),
                RequestUtils::addRoute($target_url, POP_SOCIALNETWORK_ROUTE_FOLLOWERS)
            );
            $content .= $user_html;

            $content .= '<br/>';
            $content .= TranslationAPIFacade::getInstance()->__('<p>This user will receive notifications following your activity, such as recommending content, posting a new discussion or comment, and others.</p>', 'pop-emailsender');

            $email = $userTypeAPI->getUserEmail($target_id);
            PoP_EmailSender_Utils::sendemailToUsers($email, $target_name, $subject, $content);

            // Add the users to the list of users who got an email sent to
            PoP_EmailSender_SentEmailsManager::addSentemailUsers(POP_EMAIL_FOLLOWSUSER, array($target_id));
        }
    }

    /**
     * Recommend post
     */
    public function recommendpost($post_id)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $vars = ApplicationState::getVars();
        $user_id = $vars['current-user-id'];
        $user_html = PoP_EmailTemplatesFactory::getInstance()->getUserhtml($user_id);

        $post_name = gdGetPostname($post_id);
        $subject = sprintf(TranslationAPIFacade::getInstance()->__('Your %s was recommended!'), $post_name);
        $content = sprintf(
            TranslationAPIFacade::getInstance()->__('<p>Your %1$s <a href="%2$s">%3$s</a> was recommended by:</p>', 'pop-emailsender'),
            $post_name,
            $customPostTypeAPI->getPermalink($post_id),
            $customPostTypeAPI->getTitle($post_id)
        );
        $content .= $user_html;

        $authors = PoP_EmailSender_Utils::sendemailToUsersFromPost($post_id, $subject, $content, PoP_EmailSender_SentEmailsManager::getSentemailUsers(POP_EMAIL_RECOMMENDSPOST));

        // Add the users to the list of users who got an email sent to
        PoP_EmailSender_SentEmailsManager::addSentemailUsers(POP_EMAIL_RECOMMENDSPOST, $authors);
    }
}

/**
 * Initialization
 */
new PoP_SocialNetwork_EmailSender_Hooks();

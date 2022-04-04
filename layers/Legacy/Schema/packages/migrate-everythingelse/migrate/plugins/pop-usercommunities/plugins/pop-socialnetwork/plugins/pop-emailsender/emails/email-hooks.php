<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

define('POP_EMAIL_JOINSCOMMUNITIES', 'joinscommunities');

class PoP_URE_EmailSender_Hooks
{
    public function __construct()
    {

        //----------------------------------------------------------------------
        // Email Notifications
        //----------------------------------------------------------------------
        \PoP\Root\App::addAction('gd_update_mycommunities:update', $this->emailnotificationsNetworkJoinscommunity(...), 10, 3);
    }

    public function emailnotificationsNetworkJoinscommunity($user_id, $form_data, $operationlog)
    {
        if (!$communities = $operationlog['new-communities']) {
            return;
        }

        $userTypeAPI = UserTypeAPIFacade::getInstance();
        // Get the current user's network's users (followers + members of same communities)
        $networkusers = PoP_SocialNetwork_EmailUtils::getUserNetworkusers($user_id);
        if ($networkusers = array_diff($networkusers, PoP_EmailSender_SentEmailsManager::getSentemailUsers(POP_EMAIL_JOINSCOMMUNITIES))) {
            // Keep only the users with the corresponding preference on
            if ($networkusers = PoP_UserPlatform_UserPreferencesUtils::getPreferenceonUsers(POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY, $networkusers)) {
                $emails = $names = array();
                foreach ($networkusers as $networkuser) {
                    $emails[] = $userTypeAPI->getUserEmail($networkuser);
                    $names[] = $userTypeAPI->getUserDisplayName($networkuser);
                }

                $user_url = $userTypeAPI->getUserURL($user_id);
                $user_name = $userTypeAPI->getUserDisplayName($user_id);
                $community_names = array();
                foreach ($communities as $community) {
                    $community_names[] = $userTypeAPI->getUserDisplayName($community);
                }
                $subject = sprintf(
                    TranslationAPIFacade::getInstance()->__('%s has joined %s', 'pop-emailsender'),
                    $user_name,
                    implode(
                        TranslationAPIFacade::getInstance()->__(', ', 'pop-emailsender'),
                        $community_names
                    )
                );
                
                $content = sprintf(
                    TranslationAPIFacade::getInstance()->__('<p><a href="%s">%s</a> has joined:</p>', 'pop-emailsender'),
                    $user_url,
                    $user_name
                );

                $instance = PoP_EmailTemplatesFactory::getInstance();
                foreach ($communities as $community) {
                    $content .= $instance->getUserhtml($community);
                }

                $footer = PoP_UserPlatform_EmailSenderUtils::getPreferencesFooter(TranslationAPIFacade::getInstance()->__('You are receiving this notification for belonging to the user’s network.', 'pop-emailsender'));
                $content .= $footer;
                
                PoP_EmailSender_Utils::sendemailToUsers($emails, $names, $subject, $content);

                // Add the users to the list of users who got an email sent to
                PoP_EmailSender_SentEmailsManager::addSentemailUsers(POP_EMAIL_JOINSCOMMUNITIES, $networkusers);
            }
        }
    }
}

/**
 * Initialization
 */
new PoP_URE_EmailSender_Hooks();

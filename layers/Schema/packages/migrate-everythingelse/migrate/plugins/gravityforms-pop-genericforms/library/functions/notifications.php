<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

// These are Notification names as defined in the Gravity Forms settings for the form
define('GD_GF_NOTIFICATION_PROFILES', 'Notification to Profiles');
define('GD_GF_NOTIFICATION_POSTAUTHORS', 'Notification to Post Owners');
define('GD_GF_NOTIFICATION_DESTINATIONEMAIL', 'Notification to Destination Email');

HooksAPIFacade::getInstance()->addFilter("gform_notification", "gdGfChangeAutoresponderEmailProfiles", 10, 3);
function gdGfChangeAutoresponderEmailProfiles($notification, $form, $entry)
{
    if ($notification['name'] == GD_GF_NOTIFICATION_PROFILES) {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        if ($profiles_ids = $_POST[POP_INPUTNAME_USERID] ?? []) {
            $emails = array();
            $profiles = explode(',', $profiles_ids);
            foreach ($profiles as $profile_id) {
                $emails[] = $cmsusersapi->getUserEmail($profile_id);
            }

            $notification['to'] = implode(', ', $emails);
        }
    }

    return $notification;
}

HooksAPIFacade::getInstance()->addFilter("gform_notification", "gdGfChangeAutoresponderEmailPostowners", 10, 3);
function gdGfChangeAutoresponderEmailPostowners($notification, $form, $entry)
{
    if ($notification['name'] == GD_GF_NOTIFICATION_POSTAUTHORS) {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        if ($post_ids = $_POST[POP_INPUTNAME_POSTID] ?? []) {
            $emails = array();
            foreach (explode(',', $post_ids) as $post_id) {
                $profiles = gdGetPostauthors($post_id);
                foreach ($profiles as $profile_id) {
                    $emails[] = $cmsusersapi->getUserEmail($profile_id);
                }
            }

            $notification['to'] = implode(', ', $emails);
        }
    }

    return $notification;
}


// Add the general layout of the MESYM newsletters to the email
HooksAPIFacade::getInstance()->addFilter("gform_notification", "gdGfEmailLayout", 10, 3);
function gdGfEmailLayout($notification, $form, $entry)
{
    $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
    $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
    $title = sprintf(
        TranslationAPIFacade::getInstance()->__('Notification from %s', 'gravityforms-pop-genericforms'),
        $cmsapplicationapi->getSiteName()
    );
    $names = array();
    $user_ids = array();
    $emails = array();

    // Check if the recipient of the email is known. If so, extract their names
    if ($notification['name'] == GD_GF_NOTIFICATION_PROFILES) {
        if ($ids = $_POST[POP_INPUTNAME_USERID] ?? []) {
            $user_ids = explode(',', $ids);
        }
    } elseif ($notification['name'] == GD_GF_NOTIFICATION_POSTAUTHORS) {
        if ($post_ids = $_POST[POP_INPUTNAME_POSTID] ?? []) {
            foreach (explode(',', $post_ids) as $post_id) {
                $user_ids = array_merge(
                    $user_ids,
                    gdGetPostauthors($post_id)
                );
            }
        }
    }
    if ($user_ids) {
        foreach ($user_ids as $user_id) {
            $names[] = $cmsusersapi->getUserDisplayName($user_id);
            $emails[] = $cmsusersapi->getUserEmail($user_id);
        }
    }

    $notification['message'] = PoP_EmailTemplatesFactory::getInstance()->addEmailframe($title, $notification['message'], $emails, $names, POP_EMAILTEMPLATE_EMAILBODY);

    return $notification;
}

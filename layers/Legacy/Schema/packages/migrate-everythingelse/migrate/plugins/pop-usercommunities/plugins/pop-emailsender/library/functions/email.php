<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

/**
 * Create / Update Post
 */

// Send an email to the new Communities: when the user updated the communities
\PoP\Root\App::addAction('gd_update_mycommunities:update', 'gdUreSendemailUpdatemycommunities', 100, 3);
function gdUreSendemailUpdatemycommunities($user_id, $form_data, $operationlog)
{
    gdUreSendemailCommunityNewmember($user_id, $operationlog['new-communities']);
}
function gdUreSendemailCommunityNewmember($user_id, $communities)
{
    if (!$communities) {
        return;
    }

    $userTypeAPI = UserTypeAPIFacade::getInstance();
    $author_name = $userTypeAPI->getUserDisplayName($user_id);
    $user_html = PoP_EmailTemplatesFactory::getInstance()->getUserhtml($user_id);
    
    foreach ($communities as $community) {
        // New Community => Send an email informing of the new member
        $community_url = $userTypeAPI->getUserURL($community);
        $community_name = $userTypeAPI->getUserDisplayName($community);
        $subject = sprintf(TranslationAPIFacade::getInstance()->__('%s has a new member!', 'ure-pop'), $community_name);
        
        $community_html = sprintf(
            '<a href="%s">%s</a>',
            $community_url,
            $community_name
        );
    
        $content = sprintf(
            TranslationAPIFacade::getInstance()->__('<p>Congratulations! <a href="%s">Your community has a new member</a>:</p>', 'ure-pop'),
            RequestUtils::addRoute($community_url, POP_USERCOMMUNITIES_ROUTE_MEMBERS)
        );
        $content .= $user_html;
        $content .= '<br/>';
        $content .= sprintf(
            TranslationAPIFacade::getInstance()->__('<p>Please <a href="%s">click here to configure the settings of this user as a member of your community</a>:</p>', 'ure-pop'),
            gdUreEditMembershipUrl($user_id)
        );
        $content .= '<ul>';
        $content .= sprintf(
            TranslationAPIFacade::getInstance()->__('<li>Accept or Reject <b>%s</b> as a member of your community. Currently: accepted.</li>', 'ure-pop'),
            $author_name,
            $community_html
        );
        $content .= sprintf(
            TranslationAPIFacade::getInstance()->__('<li>Determine if the content posted by <b>%s</b> will also appear under %s. Currently: yes.</li>', 'ure-pop'),
            $author_name,
            $community_html
        );
        $content .= sprintf(
            TranslationAPIFacade::getInstance()->__('<li>Choose what type of member <b>%s</b> is (Staff, Volunteer, Student, etc). Currently: \'Member\'.</li>', 'ure-pop'),
            $author_name,
            $community_html
        );
        $content .= '</ul>';
        $content .= sprintf(
            TranslationAPIFacade::getInstance()->__('<p>To view all your current members, and edit their membership settings, please click on <a href="%s">%s</a>.</p>', 'ure-pop'),
            RouteUtils::getRouteURL(POP_USERCOMMUNITIES_ROUTE_MYMEMBERS),
            RouteUtils::getRouteTitle(POP_USERCOMMUNITIES_ROUTE_MYMEMBERS)
        );
    
        $email = $userTypeAPI->getUserEmail($community);
        PoP_EmailSender_Utils::sendemailToUsers($email, $community_name, $subject, $content);
    }
}

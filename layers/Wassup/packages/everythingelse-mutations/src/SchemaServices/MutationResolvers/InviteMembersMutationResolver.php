<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\ComponentModel\Misc\RequestUtils;

class InviteMembersMutationResolver extends AbstractEmailInviteMutationResolver
{
    protected function getEmailContent($form_data)
    {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $cmsapplicationhelpers = \PoP\Application\HelperAPIFactory::getInstance();
        // The user must be always logged in, so we will have the user_id
        $user_id = $form_data['user_id'];

        $author_url = $cmsusersapi->getUserURL($user_id);
        $author_name = $cmsusersapi->getUserDisplayName($user_id);

        $user_html = \PoP_EmailTemplatesFactory::getInstance()->getUserhtml($user_id);//PoP_EmailUtils::get_user_html($user_id);
        $website_html = \PoP_EmailTemplatesFactory::getInstance()->getWebsitehtml();//PoP_EmailUtils::get_website_html();

        $content = sprintf(
            TranslationAPIFacade::getInstance()->__('<p><a href="%s">%s</a> is inviting you to <a href="%s">become their member</a>:</p>', 'ure-pop'),
            $author_url,
            $author_name,
            RequestUtils::addRoute($author_url, POP_USERCOMMUNITIES_ROUTE_MEMBERS)
        );
        // Optional: Additional Message
        if ($additional_msg = $form_data['additional-msg']) {
            $content .= sprintf(
                '<div style="margin-left: 20px;">%s</div>',
                $cmsapplicationhelpers->makeClickable($cmsapplicationhelpers->convertLinebreaksToHTML($additional_msg))
            );
        }
        $content .= $user_html;
        $content .= '<br/>';
        $content .= sprintf(
            '<h3>%s</h3>',
            sprintf(
                TranslationAPIFacade::getInstance()->__('How to become %s\'s member?', 'ure-pop'),
                $author_name
            )
        );
        $content .= '<ul><li>';
        $content .= sprintf(
            TranslationAPIFacade::getInstance()->__('If you have not registered in %s yet:<br/><a href="%s">Create your account</a>, and while doing so, select <strong>%s</strong> in section "%s".', 'ure-pop'),
            $website_html,
            RouteUtils::getRouteURL(POP_USERPLATFORM_ROUTE_ADDPROFILE),
            $author_name,
            RouteUtils::getRouteTitle(POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES)
        );
        $content .= '</li><li>';
        $content .= sprintf(
            TranslationAPIFacade::getInstance()->__('<p>If you have already have an account in %s:<br/>Go to <a href="%s">%s</a>, select <strong>%s</strong> and submit.</p>', 'ure-pop'),
            $website_html,
            RouteUtils::getRouteURL(POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES),
            RouteUtils::getRouteTitle(POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES),
            $author_name
        );
        $content .= '</li></ul>';

        return $content;
    }

    protected function getEmailSubject($form_data)
    {

        // The user must be always logged in, so we will have the user_id
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $user_id = $form_data['user_id'];
        return sprintf(
            TranslationAPIFacade::getInstance()->__('%s is inviting you to become their member!', 'ure-pop'),
            $cmsusersapi->getUserDisplayName($user_id)
        );
    }
}

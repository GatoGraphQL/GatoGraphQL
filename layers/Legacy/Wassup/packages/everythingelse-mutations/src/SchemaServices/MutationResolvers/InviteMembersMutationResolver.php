<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP_EmailTemplatesFactory;
use PoP\Application\HelperAPIFactory;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\Engine\Route\RouteUtils;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;

class InviteMembersMutationResolver extends AbstractEmailInviteMutationResolver
{
    private ?UserTypeAPIInterface $userTypeAPI = null;
    
    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        if ($this->userTypeAPI === null) {
            /** @var UserTypeAPIInterface */
            $userTypeAPI = $this->instanceManager->getInstance(UserTypeAPIInterface::class);
            $this->userTypeAPI = $userTypeAPI;
        }
        return $this->userTypeAPI;
    }
    
    protected function getEmailContent(FieldDataAccessorInterface $fieldDataAccessor): string
    {
        $cmsapplicationhelpers = HelperAPIFactory::getInstance();
        // The user must be always logged in, so we will have the user_id
        $user_id = $fieldDataAccessor->getValue('user_id');

        $author_url = $this->getUserTypeAPI()->getUserURL($user_id);
        $author_name = $this->getUserTypeAPI()->getUserDisplayName($user_id);

        $user_html = PoP_EmailTemplatesFactory::getInstance()->getUserhtml($user_id);//PoP_EmailUtils::get_user_html($user_id);
        $website_html = PoP_EmailTemplatesFactory::getInstance()->getWebsitehtml();//PoP_EmailUtils::get_website_html();

        $content = sprintf(
            $this->getTranslationAPI()->__('<p><a href="%s">%s</a> is inviting you to <a href="%s">become their member</a>:</p>', 'ure-pop'),
            $author_url,
            $author_name,
            RequestUtils::addRoute($author_url, POP_USERCOMMUNITIES_ROUTE_MEMBERS)
        );
        // Optional: Additional Message
        if ($additional_msg = $fieldDataAccessor->getValue('additional-msg')) {
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
                $this->getTranslationAPI()->__('How to become %s\'s member?', 'ure-pop'),
                $author_name
            )
        );
        $content .= '<ul><li>';
        $content .= sprintf(
            $this->getTranslationAPI()->__('If you have not registered in %s yet:<br/><a href="%s">Create your account</a>, and while doing so, select <strong>%s</strong> in section "%s".', 'ure-pop'),
            $website_html,
            RouteUtils::getRouteURL(POP_USERPLATFORM_ROUTE_ADDPROFILE),
            $author_name,
            RouteUtils::getRouteTitle(POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES)
        );
        $content .= '</li><li>';
        $content .= sprintf(
            $this->getTranslationAPI()->__('<p>If you have already have an account in %s:<br/>Go to <a href="%s">%s</a>, select <strong>%s</strong> and submit.</p>', 'ure-pop'),
            $website_html,
            RouteUtils::getRouteURL(POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES),
            RouteUtils::getRouteTitle(POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES),
            $author_name
        );
        $content .= '</li></ul>';

        return $content;
    }

    protected function getEmailSubject(FieldDataAccessorInterface $fieldDataAccessor): string
    {
        // The user must be always logged in, so we will have the user_id
        $user_id = $fieldDataAccessor->getValue('user_id');
        return sprintf(
            $this->getTranslationAPI()->__('%s is inviting you to become their member!', 'ure-pop'),
            $this->getUserTypeAPI()->getUserDisplayName($user_id)
        );
    }
}

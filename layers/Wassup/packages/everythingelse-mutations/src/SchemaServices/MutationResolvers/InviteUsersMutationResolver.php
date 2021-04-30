<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

class InviteUsersMutationResolver extends AbstractEmailInviteMutationResolver
{
    protected function getEmailContent($form_data)
    {
        $website_html = \PoP_EmailTemplatesFactory::getInstance()->getWebsitehtml();//PoP_EmailUtils::get_website_html();
        $cmsapplicationhelpers = \PoP\Application\HelperAPIFactory::getInstance();

        // Maybe the user is logged in, maybe not
        if ($sender_name = $form_data['sender-name']) {
            // There might be a sender URL if the user was logged in, or not
            if ($sender_url = $form_data['sender-url']) {
                $sender_html = sprintf('<a href="%s">%s</a>', $sender_url, $sender_name);
            } else {
                $sender_html = $sender_name;
            }
            $content = sprintf(
                $this->translationAPI->__('<p>%s is inviting you to join %s!</p>', 'pop-coreprocessors'),
                $sender_html,
                $website_html
            );
        } else {
            $content = sprintf(
                $this->translationAPI->__('<p>You have been invited to join %s!</p>', 'pop-coreprocessors'),
                $website_html
            );
        }

        // Allow Organik Fundraising to override the content
        $content = $this->hooksAPI->applyFilters(
            'GD_InviteUsers:emailcontent',
            $content,
            $sender_html,
            $website_html
        );

        // Optional: Additional Message
        if ($additional_msg = $form_data['additional-msg']) {
            $content .= sprintf(
                '<div style="margin-left: 20px;">%s</div>',
                $cmsapplicationhelpers->makeClickable($cmsapplicationhelpers->convertLinebreaksToHTML($additional_msg))
            );
            $content .= '<br/>';
        }

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $content .= sprintf(
            '<h3>%s</h3>',
            sprintf(
                $this->translationAPI->__('What is %s?', 'pop-coreprocessors'),
                $cmsapplicationapi->getSiteName()
            )
        );
        $content .= gdGetWebsiteDescription();
        $content .= '<br/><br/>';

        $btn_title = $this->translationAPI->__('Check it out here', 'pop-coreprocessors');
        $content .= \PoP_EmailTemplatesFactory::getInstance()->getButtonhtml($btn_title, $cmsengineapi->getSiteURL());

        return $content;
    }

    protected function getEmailSubject($form_data)
    {
        $subject = '';

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        // Maybe the user is logged in, maybe not
        if ($sender_name = $form_data['sender-name']) {
            $subject = sprintf(
                $this->translationAPI->__('%s is inviting you to join %s!', 'pop-coreprocessors'),
                $sender_name,
                $cmsapplicationapi->getSiteName()
            );
        } else {
            $subject = sprintf(
                $this->translationAPI->__('You have been invited to join %s!', 'pop-coreprocessors'),
                $cmsapplicationapi->getSiteName()
            );
        }

        // Allow Organik Fundraising to override the message
        return $this->hooksAPI->applyFilters(
            'GD_InviteUsers:emailsubject',
            $subject,
            $sender_name
        );
    }
}

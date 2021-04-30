<?php

declare(strict_types=1);

namespace PoPSitesWassup\NewsletterMutations\MutationResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class NewsletterSubscriptionMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(array $form_data): ?array
    {
        $errors = [];
        if (empty($form_data['email'])) {
            $errors[] = $this->translationAPI->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = $this->translationAPI->__('Email format is incorrect.', 'pop-genericforms');
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($form_data)
    {
        HooksAPIFacade::getInstance()->doAction('pop_subscribetonewsletter', $form_data);
    }

    protected function doExecute($form_data)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $to = \PoP_EmailSender_Utils::getAdminNotificationsEmail();
        $subject = sprintf(
            $this->translationAPI->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            $this->translationAPI->__('Newsletter Subscription', 'pop-genericforms')
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            $this->translationAPI->__('User subscribed to newsletter', 'pop-genericforms')
        ) . sprintf(
            $placeholder,
            $this->translationAPI->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $form_data['email']
            )
        ) . sprintf(
            $placeholder,
            $this->translationAPI->__('Name', 'pop-genericforms'),
            $form_data['name']
        );

        return \PoP_EmailSender_Utils::sendEmail($to, $subject, $msg);
    }

    public function execute(array $form_data): mixed
    {
        $result = $this->doExecute($form_data);

        // Allow for additional operations
        $this->additionals($form_data);

        return $result;
    }
}

<?php

declare(strict_types=1);

namespace PoPSitesWassup\NewsletterMutations\MutationResolvers;

use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class NewsletterUnsubscriptionMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(array $form_data): array
    {
        $errors = [];
        if (empty($form_data['email'])) {
            $errors[] = $this->getTranslationAPI()->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = $this->getTranslationAPI()->__('Email format is incorrect.', 'pop-genericforms');
        }

        $placeholder_string = $this->getTranslationAPI()->__('%s %s', 'pop-genericforms');
        $makesure_string = $this->getTranslationAPI()->__('Please make sure you have clicked on the unsubscription link in the newsletter.', 'pop-genericforms');
        if (empty($form_data['verificationcode'])) {
            $errors[] = sprintf(
                $placeholder_string,
                $this->getTranslationAPI()->__('The verification code is missing.', 'pop-genericforms'),
                $makesure_string
            );
        }

        if ($errors) {
            return $errors;
        }

        // Verify that the verification code corresponds to the email
        $verificationcode = \PoP_GenericForms_NewsletterUtils::getEmailVerificationcode($form_data['email']);
        if ($verificationcode != $form_data['verificationcode']) {
            $errors[] = sprintf(
                $placeholder_string,
                $this->getTranslationAPI()->__('The verification code does not match the email.', 'pop-genericforms'),
                $makesure_string
            );
        }
        if ($errors) {
            return $errors;
        }

        $newsletter_data = $this->getNewsletterData($form_data);
        $this->validateData($errors, $newsletter_data);
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($form_data): void
    {
        $this->getHooksAPI()->doAction('pop_unsubscribe_from_newsletter', $form_data);
    }

    /**
     * Function to override by Gravity Forms
     */
    protected function getNewsletterData($form_data)
    {
        return array();
    }
    /**
     * Function to override by Gravity Forms
     */
    protected function validateData(&$errors, $newsletter_data): void
    {
    }

    protected function doExecute(array $newsletter_data)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $to = \PoP_EmailSender_Utils::getAdminNotificationsEmail();
        $subject = sprintf(
            $this->getTranslationAPI()->__('[%s]: Newsletter unsubscription', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName()
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            $this->getTranslationAPI()->__('User unsubscribed from newsletter', 'pop-genericforms')
        ) . sprintf(
            $placeholder,
            $this->getTranslationAPI()->__('Email', 'pop-genericforms'),
            $newsletter_data['email']
        );

        return \PoP_EmailSender_Utils::sendEmail($to, $subject, $msg);
        // return GFAPI::delete_entry($newsletter_data['entry-id']);
    }

    public function executeMutation(array $form_data): mixed
    {
        $newsletter_data = $this->getNewsletterData($form_data);
        $result = $this->doExecute($newsletter_data);

        // Allow for additional operations
        $this->additionals($form_data);

        return $result;
    }
}

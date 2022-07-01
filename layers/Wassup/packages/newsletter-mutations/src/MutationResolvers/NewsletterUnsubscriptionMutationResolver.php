<?php

declare(strict_types=1);

namespace PoPSitesWassup\NewsletterMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP_GenericForms_NewsletterUtils;
use PoP_EmailSender_Utils;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class NewsletterUnsubscriptionMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(MutationDataProviderInterface $mutationDataProvider): array
    {
        $errors = [];
        if (empty($mutationDataProvider->get('email'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($mutationDataProvider->get('email'), FILTER_VALIDATE_EMAIL)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Email format is incorrect.', 'pop-genericforms');
        }

        $placeholder_string = $this->__('%s %s', 'pop-genericforms');
        $makesure_string = $this->__('Please make sure you have clicked on the unsubscription link in the newsletter.', 'pop-genericforms');
        if (empty($mutationDataProvider->get('verificationcode'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = sprintf(
                $placeholder_string,
                $this->__('The verification code is missing.', 'pop-genericforms'),
                $makesure_string
            );
        }

        if ($errors) {
            return $errors;
        }

        // Verify that the verification code corresponds to the email
        $verificationcode = PoP_GenericForms_NewsletterUtils::getEmailVerificationcode($mutationDataProvider->get('email'));
        if ($verificationcode != $mutationDataProvider->get('verificationcode')) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = sprintf(
                $placeholder_string,
                $this->__('The verification code does not match the email.', 'pop-genericforms'),
                $makesure_string
            );
        }
        if ($errors) {
            return $errors;
        }

        $newsletter_data = $this->getNewsletterData($mutationDataProvider);
        $this->validateData($errors, $newsletter_data);
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals(MutationDataProviderInterface $mutationDataProvider): void
    {
        App::doAction('pop_unsubscribe_from_newsletter', $mutationDataProvider);
    }

    /**
     * Function to override by Gravity Forms
     */
    protected function getNewsletterData(MutationDataProviderInterface $mutationDataProvider)
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
        $to = PoP_EmailSender_Utils::getAdminNotificationsEmail();
        $subject = sprintf(
            $this->__('[%s]: Newsletter unsubscription', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName()
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            $this->__('User unsubscribed from newsletter', 'pop-genericforms')
        ) . sprintf(
            $placeholder,
            $this->__('Email', 'pop-genericforms'),
            $newsletter_data['email']
        );

        return PoP_EmailSender_Utils::sendEmail($to, $subject, $msg);
        // return GFAPI::delete_entry($newsletter_data['entry-id']);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(MutationDataProviderInterface $mutationDataProvider): mixed
    {
        $newsletter_data = $this->getNewsletterData($mutationDataProvider);
        $result = $this->doExecute($newsletter_data);

        // Allow for additional operations
        $this->additionals($mutationDataProvider);

        return $result;
    }
}

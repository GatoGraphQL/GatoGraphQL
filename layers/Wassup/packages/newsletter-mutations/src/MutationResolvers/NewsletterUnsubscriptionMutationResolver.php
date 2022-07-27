<?php

declare(strict_types=1);

namespace PoPSitesWassup\NewsletterMutations\MutationResolvers;

use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP_EmailSender_Utils;
use PoP_GenericForms_NewsletterUtils;

class NewsletterUnsubscriptionMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (empty($fieldDataAccessor->getValue('email'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($fieldDataAccessor->getValue('email'), FILTER_VALIDATE_EMAIL)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->__('Email format is incorrect.', 'pop-genericforms');
        }

        $placeholder_string = $this->__('%s %s', 'pop-genericforms');
        $makesure_string = $this->__('Please make sure you have clicked on the unsubscription link in the newsletter.', 'pop-genericforms');
        if (empty($fieldDataAccessor->getValue('verificationcode'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = sprintf(
                $placeholder_string,
                $this->__('The verification code is missing.', 'pop-genericforms'),
                $makesure_string
            );
        }

        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return;
        }

        // Verify that the verification code corresponds to the email
        $verificationcode = PoP_GenericForms_NewsletterUtils::getEmailVerificationcode($fieldDataAccessor->getValue('email'));
        if ($verificationcode != $fieldDataAccessor->getValue('verificationcode')) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = sprintf(
                $placeholder_string,
                $this->__('The verification code does not match the email.', 'pop-genericforms'),
                $makesure_string
            );
        }
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return;
        }

        $newsletter_data = $this->getNewsletterData($fieldDataAccessor);
        $this->validateData($newsletter_data, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Function to override
     */
    protected function additionals(FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('pop_unsubscribe_from_newsletter', $fieldDataAccessor);
    }

    /**
     * Function to override by Gravity Forms
     */
    protected function getNewsletterData(FieldDataAccessorInterface $fieldDataAccessor)
    {
        return array();
    }
    /**
     * Function to override by Gravity Forms
     */
    protected function validateData(array $newsletter_data, ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore): void
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
    public function executeMutation(FieldDataAccessorInterface $fieldDataAccessor): mixed
    {
        $newsletter_data = $this->getNewsletterData($fieldDataAccessor);
        $result = $this->doExecute($newsletter_data);

        // Allow for additional operations
        $this->additionals($fieldDataAccessor);

        return $result;
    }
}

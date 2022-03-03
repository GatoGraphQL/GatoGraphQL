<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP\Root\Exception\GenericClientException;

abstract class AbstractEmailInviteMutationResolver extends AbstractMutationResolver
{
    /**
     * @param array<string,mixed> $form_data
     * @throws AbstractException In case of error
     */
    public function executeMutation(array $form_data): mixed
    {
        $emails = $form_data['emails'];
        // Remove the invalid emails
        $emails = array_diff($emails, $this->getInvalidEmails($emails));
        if (!empty($emails)) {
            $subject = $this->getEmailSubject($form_data);
            $content = $this->getEmailContent($form_data);
            \PoP_EmailSender_Utils::sendemailToUsers($emails, array(), $subject, $content, true);
            return true;
        }
        return false;
    }

    protected function validateCaptcha(&$errors, &$form_data): void
    {
        // Validate the captcha
        if (!\PoP_FormUtils::useLoggedinuserData() || !App::getState('is-user-logged-in')) {
            $captcha = $form_data['captcha'];
            try {
                \GD_Captcha::assertIsValid($captcha);
            } catch (GenericClientException $e) {
                $errors[] = $e->getMessage();
            }
        }
    }

    public function validateErrors(array $form_data): array
    {
        $errors = [];
        // We validate the captcha apart, since if it fails, then we must not send any invite to anyone (see below: email is sent even if validation fails)
        $this->validateCaptcha($errors, $form_data);

        if ($errors) {
            return $errors;
        }

        $emails = $form_data['emails'];
        if (empty($emails)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->getTranslationAPI()->__('Email(s) cannot be empty.', 'pop-coreprocessors');
        }

        return $errors;
    }

    protected function getInvalidEmails(array $emails): array
    {
        return array_filter(
            $emails,
            function (string $email): bool {
                return !is_email($email);
            }
        );
    }

    /**
     * @return FeedbackItemResolution[]
     */
    public function validateWarnings(array $form_data): array
    {
        $warnings = [];

        $emails = $form_data['emails'];
        if ($invalid_emails = $this->getInvalidEmails($emails)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $warnings[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $warnings[] = sprintf(
                $this->getTranslationAPI()->__('The following emails are invalid: <strong>%s</strong>', 'pop-coreprocessors'),
                implode(', ', $invalid_emails)
            );
        }

        return $warnings;
    }

    abstract protected function getEmailContent($form_data);

    abstract protected function getEmailSubject($form_data);
}

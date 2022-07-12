<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_EmailSender_Utils;
use PoP_FormUtils;
use GD_Captcha;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP\Root\Exception\GenericClientException;

abstract class AbstractEmailInviteMutationResolver extends AbstractMutationResolver
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataAccessorInterface $fieldDataAccessor): mixed
    {
        $emails = $fieldDataAccessor->getValue('emails');
        // Remove the invalid emails
        $emails = array_diff($emails, $this->getInvalidEmails($emails));
        if (!empty($emails)) {
            $subject = $this->getEmailSubject($fieldDataAccessor);
            $content = $this->getEmailContent($fieldDataAccessor);
            PoP_EmailSender_Utils::sendemailToUsers($emails, array(), $subject, $content, true);
            return true;
        }
        return false;
    }

    protected function validateCaptcha(&$errors, &$fieldDataAccessor): void
    {
        // Validate the captcha
        if (!PoP_FormUtils::useLoggedinuserData() || !App::getState('is-user-logged-in')) {
            $captcha = $fieldDataAccessor->getValue('captcha');
            try {
                GD_Captcha::assertIsValid($captcha);
            } catch (GenericClientException $e) {
                $errors[] = $e->getMessage();
            }
        }
    }

    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void
    {
        $errors = [];
        // We validate the captcha apart, since if it fails, then we must not send any invite to anyone (see below: email is sent even if validation fails)
        $this->validateCaptcha($errors, $fieldDataAccessor);

        if ($errors) {
            return $errors;
        }

        $emails = $fieldDataAccessor->getValue('emails');
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
    public function validateWarnings(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $warnings = [];

        $emails = $fieldDataAccessor->getValue('emails');
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

    abstract protected function getEmailContent(FieldDataAccessorInterface $fieldDataAccessor);

    abstract protected function getEmailSubject(FieldDataAccessorInterface $fieldDataAccessor);
}

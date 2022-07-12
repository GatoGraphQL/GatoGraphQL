<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use GD_Captcha;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\GenericFeedbackItemProvider;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP\Root\Exception\GenericClientException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP_EmailSender_Utils;
use PoP_FormUtils;

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

    protected function validateCaptcha(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // Validate the captcha
        if (!PoP_FormUtils::useLoggedinuserData() || !App::getState('is-user-logged-in')) {
            $captcha = $fieldDataAccessor->getValue('captcha');
            try {
                GD_Captcha::assertIsValid($captcha);
            } catch (GenericClientException $e) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            GenericFeedbackItemProvider::class,
                            GenericFeedbackItemProvider::E1,
                            [
                                $e->getMessage(),
                            ]
                        ),
                        $fieldDataAccessor->getField(),
                    )
                );
            }
        }
    }

    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // We validate the captcha apart, since if it fails, then we must not send any invite to anyone (see below: email is sent even if validation fails)
        $this->validateCaptcha($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return;
        }

        $emails = $fieldDataAccessor->getValue('emails');
        if (empty($emails)) {
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
            $errors[] = $this->getTranslationAPI()->__('Email(s) cannot be empty.', 'pop-coreprocessors');
        }
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

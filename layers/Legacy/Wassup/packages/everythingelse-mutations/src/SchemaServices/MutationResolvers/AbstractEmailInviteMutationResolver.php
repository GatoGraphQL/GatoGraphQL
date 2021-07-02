<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\State\ApplicationState;

abstract class AbstractEmailInviteMutationResolver extends AbstractMutationResolver
{
    public function execute(array $form_data): mixed
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

    protected function validateCaptcha(&$errors, &$form_data)
    {
        // Validate the captcha
        $vars = ApplicationState::getVars();
        if (!\PoP_FormUtils::useLoggedinuserData() || !$vars['global-userstate']['is-user-logged-in']) {
            $captcha = $form_data['captcha'];

            $captcha_validation = \GD_Captcha::validate($captcha);
            if (GeneralUtils::isError($captcha_validation)) {
                /** @var Error */
                $error = $captcha_validation;
                $errors[] = $error->getMessageOrCode();
            }
        }
    }

    public function validateErrors(array $form_data): ?array
    {
        $errors = [];
        // We validate the captcha apart, since if it fails, then we must not send any invite to anyone (see below: email is sent even if validation fails)
        $this->validateCaptcha($errors, $form_data);

        if ($errors) {
            return $errors;
        }

        $emails = $form_data['emails'];
        if (empty($emails)) {
            $errors[] = $this->translationAPI->__('Email(s) cannot be empty.', 'pop-coreprocessors');
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

    public function validateWarnings(array $form_data): ?array
    {
        $warnings = [];

        $emails = $form_data['emails'];
        if ($invalid_emails = $this->getInvalidEmails($emails)) {
            $warnings[] = sprintf(
                $this->translationAPI->__('The following emails are invalid: <strong>%s</strong>', 'pop-coreprocessors'),
                implode(', ', $invalid_emails)
            );
        }

        return $warnings;
    }

    abstract protected function getEmailContent($form_data);

    abstract protected function getEmailSubject($form_data);
}

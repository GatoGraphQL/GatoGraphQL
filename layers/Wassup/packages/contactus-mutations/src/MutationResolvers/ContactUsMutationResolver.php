<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUsMutations\MutationResolvers;

use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class ContactUsMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(array $form_data): array
    {
        $errors = [];
        if (empty($form_data['name'])) {
            $errors[] = $this->getTranslationAPI()->__('Your name cannot be empty.', 'pop-genericforms');
        }
        if (empty($form_data['email'])) {
            $errors[] = $this->getTranslationAPI()->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = $this->getTranslationAPI()->__('Email format is incorrect.', 'pop-genericforms');
        }
        if (empty($form_data['message'])) {
            $errors[] = $this->getTranslationAPI()->__('Message cannot be empty.', 'pop-genericforms');
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($form_data): void
    {
        $this->getHooksAPI()->doAction('pop_contactus', $form_data);
    }

    protected function doExecute($form_data)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $to = \PoP_EmailSender_Utils::getAdminNotificationsEmail();
        $subject = sprintf(
            $this->getTranslationAPI()->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            $this->getTranslationAPI()->__('Contact us', 'pop-genericforms')
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            $this->getTranslationAPI()->__('New contact us submission', 'pop-genericforms')
        ) . sprintf(
            $placeholder,
            $this->getTranslationAPI()->__('Name', 'pop-genericforms'),
            $form_data['name']
        ) . sprintf(
            $placeholder,
            $this->getTranslationAPI()->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $form_data['email']
            )
        ) . sprintf(
            $placeholder,
            $this->getTranslationAPI()->__('Subject', 'pop-genericforms'),
            $form_data['subject']
        ) . sprintf(
            $placeholder,
            $this->getTranslationAPI()->__('Message', 'pop-genericforms'),
            $form_data['message']
        );

        return \PoP_EmailSender_Utils::sendEmail($to, $subject, $msg);
    }

    public function executeMutation(array $form_data): mixed
    {
        $result = $this->doExecute($form_data);

        // Allow for additional operations
        $this->additionals($form_data);

        return $result;
    }
}

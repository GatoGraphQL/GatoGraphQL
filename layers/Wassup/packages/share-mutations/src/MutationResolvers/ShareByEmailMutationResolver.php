<?php

declare(strict_types=1);

namespace PoPSitesWassup\ShareMutations\MutationResolvers;

use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class ShareByEmailMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(array $form_data): array
    {
        $errors = [];
        if (empty($form_data['name'])) {
            $errors[] = $this->__('Your name cannot be empty.', 'pop-genericforms');
        }

        if (empty($form_data['email'])) {
            $errors[] = $this->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = $this->__('Email format is incorrect.', 'pop-genericforms');
        }

        if (empty($form_data['target-url'])) {
            $errors[] = $this->__('The shared-page URL cannot be empty.', 'pop-genericforms');
        }

        if (empty($form_data['target-title'])) {
            $errors[] = $this->__('The shared-page title cannot be empty.', 'pop-genericforms');
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($form_data): void
    {
        App::doAction('pop_sharebyemail', $form_data);
    }

    protected function doExecute($form_data)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $subject = sprintf(
            $this->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            sprintf(
                $this->__('%s is sharing with you: %s', 'pop-genericforms'),
                $form_data['name'],
                $form_data['target-title']
            )
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            sprintf(
                $this->__('%s is sharing with you: <a href="%s">%s</a>', 'pop-genericforms'),
                $form_data['name'],
                $form_data['target-url'],
                $form_data['target-title']
            )
        ) . ($form_data['message'] ? sprintf(
            $placeholder,
            $this->__('Additional message', 'pop-genericforms'),
            $form_data['message']
        ) : '');

        return \PoP_EmailSender_Utils::sendEmail($form_data['email'], $subject, $msg);
    }

    /**
     * @param array<string,mixed> $form_data
     * @throws AbstractException In case of error
     */
    public function executeMutation(array $form_data): mixed
    {
        $result = $this->doExecute($form_data);

        // Allow for additional operations
        $this->additionals($form_data);

        return $result;
    }
}

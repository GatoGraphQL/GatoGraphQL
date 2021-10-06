<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUserMutations\MutationResolvers;

use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class ContactUserMutationResolver extends AbstractMutationResolver
{
    protected UserTypeAPIInterface $userTypeAPI;

    #[Required]
    final public function autowireContactUserMutationResolver(
        UserTypeAPIInterface $userTypeAPI,
    ): void {
        $this->userTypeAPI = $userTypeAPI;
    }

    public function validateErrors(array $form_data): array
    {
        $errors = [];
        if (empty($form_data['name'])) {
            $errors[] = $this->translationAPI->__('Your name cannot be empty.', 'pop-genericforms');
        }

        if (empty($form_data['email'])) {
            $errors[] = $this->translationAPI->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = $this->translationAPI->__('Email format is incorrect.', 'pop-genericforms');
        }

        if (empty($form_data['message'])) {
            $errors[] = $this->translationAPI->__('Message cannot be empty.', 'pop-genericforms');
        }

        if (empty($form_data['target-id'])) {
            $errors[] = $this->translationAPI->__('The requested user cannot be empty.', 'pop-genericforms');
        } else {
            $target = $this->userTypeAPI->getUserById($form_data['target-id']);
            if (!$target) {
                $errors[] = $this->translationAPI->__('The requested user does not exist.', 'pop-genericforms');
            }
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($form_data): void
    {
        $this->hooksAPI->doAction('pop_contactuser', $form_data);
    }

    protected function doExecute($form_data)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $websitename = $cmsapplicationapi->getSiteName();
        $subject = sprintf(
            $this->translationAPI->__('[%s]: %s', 'pop-genericforms'),
            $websitename,
            $form_data['subject'] ? $form_data['subject'] : sprintf(
                $this->translationAPI->__('%s sends you a message', 'pop-genericforms'),
                $form_data['name']
            )
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            sprintf(
                $this->translationAPI->__('You have been sent a message from a user in %s', 'pop-genericforms'),
                $websitename
            )
        ) . sprintf(
            $placeholder,
            $this->translationAPI->__('Name', 'pop-genericforms'),
            $form_data['name']
        ) . sprintf(
            $placeholder,
            $this->translationAPI->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $form_data['email']
            )
        ) . sprintf(
            $placeholder,
            $this->translationAPI->__('Subject', 'pop-genericforms'),
            $form_data['subject']
        ) . sprintf(
            $placeholder,
            $this->translationAPI->__('Message', 'pop-genericforms'),
            $form_data['message']
        );

        return \PoP_EmailSender_Utils::sendemailToUser($form_data['target-id'], $subject, $msg);
    }

    public function executeMutation(array $form_data): mixed
    {
        $result = $this->doExecute($form_data);

        // Allow for additional operations
        $this->additionals($form_data);

        return $result;
    }
}

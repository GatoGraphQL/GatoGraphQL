<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUserMutations\MutationResolvers;

use PoP\Root\App;
use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;

class ContactUserMutationResolver extends AbstractMutationResolver
{
    private ?UserTypeAPIInterface $userTypeAPI = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }

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

        if (empty($form_data['message'])) {
            $errors[] = $this->__('Message cannot be empty.', 'pop-genericforms');
        }

        if (empty($form_data['target-id'])) {
            $errors[] = $this->__('The requested user cannot be empty.', 'pop-genericforms');
        } else {
            $target = $this->getUserTypeAPI()->getUserById($form_data['target-id']);
            if (!$target) {
                $errors[] = $this->__('The requested user does not exist.', 'pop-genericforms');
            }
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($form_data): void
    {
        App::doAction('pop_contactuser', $form_data);
    }

    protected function doExecute($form_data)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $websitename = $cmsapplicationapi->getSiteName();
        $subject = sprintf(
            $this->__('[%s]: %s', 'pop-genericforms'),
            $websitename,
            $form_data['subject'] ? $form_data['subject'] : sprintf(
                $this->__('%s sends you a message', 'pop-genericforms'),
                $form_data['name']
            )
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            sprintf(
                $this->__('You have been sent a message from a user in %s', 'pop-genericforms'),
                $websitename
            )
        ) . sprintf(
            $placeholder,
            $this->__('Name', 'pop-genericforms'),
            $form_data['name']
        ) . sprintf(
            $placeholder,
            $this->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $form_data['email']
            )
        ) . sprintf(
            $placeholder,
            $this->__('Subject', 'pop-genericforms'),
            $form_data['subject']
        ) . sprintf(
            $placeholder,
            $this->__('Message', 'pop-genericforms'),
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

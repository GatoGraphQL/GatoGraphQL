<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUserMutations\MutationResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class ContactUserMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(array $form_data): ?array
    {
        $errors = [];
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        if (empty($form_data['name'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Your name cannot be empty.', 'pop-genericforms');
        }

        if (empty($form_data['email'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Email format is incorrect.', 'pop-genericforms');
        }

        if (empty($form_data['message'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Message cannot be empty.', 'pop-genericforms');
        }

        if (empty($form_data['target-id'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('The requested user cannot be empty.', 'pop-genericforms');
        } else {
            $target = $cmsusersapi->getUserById($form_data['target-id']);
            if (!$target) {
                $errors[] = TranslationAPIFacade::getInstance()->__('The requested user does not exist.', 'pop-genericforms');
            }
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($form_data)
    {
        HooksAPIFacade::getInstance()->doAction('pop_contactuser', $form_data);
    }

    protected function doExecute($form_data)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $websitename = $cmsapplicationapi->getSiteName();
        $subject = sprintf(
            TranslationAPIFacade::getInstance()->__('[%s]: %s', 'pop-genericforms'),
            $websitename,
            $form_data['subject'] ? $form_data['subject'] : sprintf(
                TranslationAPIFacade::getInstance()->__('%s sends you a message', 'pop-genericforms'),
                $form_data['name']
            )
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            sprintf(
                TranslationAPIFacade::getInstance()->__('You have been sent a message from a user in %s', 'pop-genericforms'),
                $websitename
            )
        ).sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Name', 'pop-genericforms'),
            $form_data['name']
        ).sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $form_data['email']
            )
        ).sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Subject', 'pop-genericforms'),
            $form_data['subject']
        ).sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Message', 'pop-genericforms'),
            $form_data['message']
        );

        return \PoP_EmailSender_Utils::sendemailToUser($form_data['target-id'], $subject, $msg);
    }

    /**
     * @return mixed
     */
    public function execute(array $form_data)
    {
        $result = $this->doExecute($form_data);

        // Allow for additional operations
        $this->additionals($form_data);

        return $result;
    }
}

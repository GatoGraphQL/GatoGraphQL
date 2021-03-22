<?php

declare(strict_types=1);

namespace PoPSitesWassup\FlagMutations\MutationResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class FlagCustomPostMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(array $form_data): ?array
    {
        $errors = [];
        if (empty($form_data['name'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Your name cannot be empty.', 'pop-genericforms');
        }

        if (empty($form_data['email'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Email format is incorrect.', 'pop-genericforms');
        }

        if (empty($form_data['whyflag'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Why flag cannot be empty.', 'pop-genericforms');
        }

        if (empty($form_data['target-id'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('The requested post cannot be empty.', 'pop-genericforms');
        } else {
            // Make sure the post exists
            $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
            $target = $customPostTypeAPI->getCustomPost($form_data['target-id']);
            if (!$target) {
                $errors[] = TranslationAPIFacade::getInstance()->__('The requested post does not exist.', 'pop-genericforms');
            }
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($form_data)
    {
        HooksAPIFacade::getInstance()->doAction('pop_flag', $form_data);
    }

    protected function doExecute($form_data)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $to = PoP_EmailSender_Utils::getAdminNotificationsEmail();
        $subject = sprintf(
            TranslationAPIFacade::getInstance()->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            TranslationAPIFacade::getInstance()->__('Flag post', 'pop-genericforms')
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            TranslationAPIFacade::getInstance()->__('New post flagged by user', 'pop-genericforms')
        ) . sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Name', 'pop-genericforms'),
            $form_data['name']
        ) . sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $form_data['email']
            )
        ) . sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Post ID', 'pop-genericforms'),
            $form_data['target-id']
        ) . sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Post title', 'pop-genericforms'),
            $customPostTypeAPI->getTitle($form_data['target-id'])
        ) . sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Why flag', 'pop-genericforms'),
            $form_data['whyflag']
        );

        return \PoP_EmailSender_Utils::sendEmail($to, $subject, $msg);
    }

    public function execute(array $form_data): mixed
    {
        $result = $this->doExecute($form_data);

        // Allow for additional operations
        $this->additionals($form_data);

        return $result;
    }
}

<?php

declare(strict_types=1);

namespace PoPSitesWassup\VolunteerMutations\MutationResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class VolunteerMutationResolver extends AbstractMutationResolver
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

        if (empty($form_data['whyvolunteer'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Why volunteer cannot be empty.', 'pop-genericforms');
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($form_data)
    {
        HooksAPIFacade::getInstance()->doAction('pop_volunteer', $form_data);
    }

    protected function doExecute($form_data)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $post_title = $customPostTypeAPI->getTitle($form_data['target-id']);
        $subject = sprintf(
            TranslationAPIFacade::getInstance()->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            sprintf(
                TranslationAPIFacade::getInstance()->__('%s applied to volunteer for %s', 'pop-genericforms'),
                $form_data['name'],
                $post_title
            )
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            TranslationAPIFacade::getInstance()->__('You have a new volunteer! Please contact the volunteer directly through the contact details below.', 'pop-genericforms')
        ).sprintf(
            '<p>%s</p>',
            sprintf(
                TranslationAPIFacade::getInstance()->__('%s applied to volunteer for: <a href="%s">%s</a>', 'pop-genericforms'),
                $form_data['name'],
                $customPostTypeAPI->getPermalink($form_data['target-id']),
                $post_title
            )
        ).sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $form_data['email']
            )
        ).sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Phone', 'pop-genericforms'),
            $form_data['phone']
        ).sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Why volunteer', 'pop-genericforms'),
            $form_data['whyvolunteer']
        );

        return \PoP_EmailSender_Utils::sendemailToUsersFromPost(array($form_data['target-id']), $subject, $msg);
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

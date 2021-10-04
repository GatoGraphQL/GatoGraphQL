<?php

declare(strict_types=1);

namespace PoPSitesWassup\FlagMutations\MutationResolvers;

use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class FlagCustomPostMutationResolver extends AbstractMutationResolver
{
    protected CustomPostTypeAPIInterface $customPostTypeAPI;

    #[Required]
    final public function autowireFlagCustomPostMutationResolver(
        CustomPostTypeAPIInterface $customPostTypeAPI,
    ): void {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }

    public function validateErrors(array $form_data): ?array
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

        if (empty($form_data['whyflag'])) {
            $errors[] = $this->translationAPI->__('Why flag cannot be empty.', 'pop-genericforms');
        }

        if (empty($form_data['target-id'])) {
            $errors[] = $this->translationAPI->__('The requested post cannot be empty.', 'pop-genericforms');
        } else {
            // Make sure the post exists
            $target = $this->customPostTypeAPI->getCustomPost($form_data['target-id']);
            if (!$target) {
                $errors[] = $this->translationAPI->__('The requested post does not exist.', 'pop-genericforms');
            }
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($form_data): void
    {
        $this->hooksAPI->doAction('pop_flag', $form_data);
    }

    protected function doExecute($form_data)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $to = \PoP_EmailSender_Utils::getAdminNotificationsEmail();
        $subject = sprintf(
            $this->translationAPI->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            $this->translationAPI->__('Flag post', 'pop-genericforms')
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            $this->translationAPI->__('New post flagged by user', 'pop-genericforms')
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
            $this->translationAPI->__('Post ID', 'pop-genericforms'),
            $form_data['target-id']
        ) . sprintf(
            $placeholder,
            $this->translationAPI->__('Post title', 'pop-genericforms'),
            $this->customPostTypeAPI->getTitle($form_data['target-id'])
        ) . sprintf(
            $placeholder,
            $this->translationAPI->__('Why flag', 'pop-genericforms'),
            $form_data['whyflag']
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

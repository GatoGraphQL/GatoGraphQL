<?php

declare(strict_types=1);

namespace PoPSitesWassup\FlagMutations\MutationResolvers;

use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

class FlagCustomPostMutationResolver extends AbstractMutationResolver
{
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
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

        if (empty($form_data['whyflag'])) {
            $errors[] = $this->__('Why flag cannot be empty.', 'pop-genericforms');
        }

        if (empty($form_data['target-id'])) {
            $errors[] = $this->__('The requested post cannot be empty.', 'pop-genericforms');
        } else {
            // Make sure the post exists
            $target = $this->getCustomPostTypeAPI()->getCustomPost($form_data['target-id']);
            if (!$target) {
                $errors[] = $this->__('The requested post does not exist.', 'pop-genericforms');
            }
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($form_data): void
    {
        App::doAction('pop_flag', $form_data);
    }

    protected function doExecute($form_data)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $to = \PoP_EmailSender_Utils::getAdminNotificationsEmail();
        $subject = sprintf(
            $this->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            $this->__('Flag post', 'pop-genericforms')
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            $this->__('New post flagged by user', 'pop-genericforms')
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
            $this->__('Post ID', 'pop-genericforms'),
            $form_data['target-id']
        ) . sprintf(
            $placeholder,
            $this->__('Post title', 'pop-genericforms'),
            $this->getCustomPostTypeAPI()->getTitle($form_data['target-id'])
        ) . sprintf(
            $placeholder,
            $this->__('Why flag', 'pop-genericforms'),
            $form_data['whyflag']
        );

        return \PoP_EmailSender_Utils::sendEmail($to, $subject, $msg);
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

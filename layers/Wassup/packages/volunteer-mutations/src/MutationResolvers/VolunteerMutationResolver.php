<?php

declare(strict_types=1);

namespace PoPSitesWassup\VolunteerMutations\MutationResolvers;

use PoP\Root\App;
use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

class VolunteerMutationResolver extends AbstractMutationResolver
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

        if (empty($form_data['target-id'])) {
            $errors[] = $this->__('The requested post cannot be empty.', 'pop-genericforms');
        } else {
            // Make sure the post exists
            $target = $this->getCustomPostTypeAPI()->getCustomPost($form_data['target-id']);
            if (!$target) {
                $errors[] = $this->__('The requested post does not exist.', 'pop-genericforms');
            }
        }

        if (empty($form_data['whyvolunteer'])) {
            $errors[] = $this->__('Why volunteer cannot be empty.', 'pop-genericforms');
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($form_data): void
    {
        App::doAction('pop_volunteer', $form_data);
    }

    protected function doExecute($form_data)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $post_title = $this->getCustomPostTypeAPI()->getTitle($form_data['target-id']);
        $subject = sprintf(
            $this->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            sprintf(
                $this->__('%s applied to volunteer for %s', 'pop-genericforms'),
                $form_data['name'],
                $post_title
            )
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            $this->__('You have a new volunteer! Please contact the volunteer directly through the contact details below.', 'pop-genericforms')
        ) . sprintf(
            '<p>%s</p>',
            sprintf(
                $this->__('%s applied to volunteer for: <a href="%s">%s</a>', 'pop-genericforms'),
                $form_data['name'],
                $this->getCustomPostTypeAPI()->getPermalink($form_data['target-id']),
                $post_title
            )
        ) . sprintf(
            $placeholder,
            $this->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $form_data['email']
            )
        ) . sprintf(
            $placeholder,
            $this->__('Phone', 'pop-genericforms'),
            $form_data['phone']
        ) . sprintf(
            $placeholder,
            $this->__('Why volunteer', 'pop-genericforms'),
            $form_data['whyvolunteer']
        );

        return \PoP_EmailSender_Utils::sendemailToUsersFromPost(array($form_data['target-id']), $subject, $msg);
    }

    public function executeMutation(array $form_data): mixed
    {
        $result = $this->doExecute($form_data);

        // Allow for additional operations
        $this->additionals($form_data);

        return $result;
    }
}

<?php

declare(strict_types=1);

namespace PoPSitesWassup\VolunteerMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP_EmailSender_Utils;
use PoP\Root\Exception\AbstractException;
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

    public function validateErrors(FieldDataAccessorInterface $fieldDataProvider): array
    {
        $errors = [];
        if (empty($fieldDataProvider->get('name'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Your name cannot be empty.', 'pop-genericforms');
        }

        if (empty($fieldDataProvider->get('email'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($fieldDataProvider->get('email'), FILTER_VALIDATE_EMAIL)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Email format is incorrect.', 'pop-genericforms');
        }

        if (empty($fieldDataProvider->get('target-id'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('The requested post cannot be empty.', 'pop-genericforms');
        } else {
            // Make sure the post exists
            $target = $this->getCustomPostTypeAPI()->getCustomPost($fieldDataProvider->get('target-id'));
            if (!$target) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = $this->__('The requested post does not exist.', 'pop-genericforms');
            }
        }

        if (empty($fieldDataProvider->get('whyvolunteer'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Why volunteer cannot be empty.', 'pop-genericforms');
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals(FieldDataAccessorInterface $fieldDataProvider): void
    {
        App::doAction('pop_volunteer', $fieldDataProvider);
    }

    protected function doExecute(FieldDataAccessorInterface $fieldDataProvider)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $post_title = $this->getCustomPostTypeAPI()->getTitle($fieldDataProvider->get('target-id'));
        $subject = sprintf(
            $this->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            sprintf(
                $this->__('%s applied to volunteer for %s', 'pop-genericforms'),
                $fieldDataProvider->get('name'),
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
                $fieldDataProvider->get('name'),
                $this->getCustomPostTypeAPI()->getPermalink($fieldDataProvider->get('target-id')),
                $post_title
            )
        ) . sprintf(
            $placeholder,
            $this->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $fieldDataProvider->get('email')
            )
        ) . sprintf(
            $placeholder,
            $this->__('Phone', 'pop-genericforms'),
            $fieldDataProvider->get('phone')
        ) . sprintf(
            $placeholder,
            $this->__('Why volunteer', 'pop-genericforms'),
            $fieldDataProvider->get('whyvolunteer')
        );

        return PoP_EmailSender_Utils::sendemailToUsersFromPost(array($fieldDataProvider->get('target-id')), $subject, $msg);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataAccessorInterface $fieldDataProvider): mixed
    {
        $result = $this->doExecute($fieldDataProvider);

        // Allow for additional operations
        $this->additionals($fieldDataProvider);

        return $result;
    }
}

<?php

declare(strict_types=1);

namespace PoPSitesWassup\VolunteerMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
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

    public function validateErrors(MutationDataProviderInterface $mutationDataProvider): array
    {
        $errors = [];
        if (empty($mutationDataProvider->getValue('name'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Your name cannot be empty.', 'pop-genericforms');
        }

        if (empty($mutationDataProvider->getValue('email'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($mutationDataProvider->getValue('email'), FILTER_VALIDATE_EMAIL)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Email format is incorrect.', 'pop-genericforms');
        }

        if (empty($mutationDataProvider->getValue('target-id'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('The requested post cannot be empty.', 'pop-genericforms');
        } else {
            // Make sure the post exists
            $target = $this->getCustomPostTypeAPI()->getCustomPost($mutationDataProvider->getValue('target-id'));
            if (!$target) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = $this->__('The requested post does not exist.', 'pop-genericforms');
            }
        }

        if (empty($mutationDataProvider->getValue('whyvolunteer'))) {
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
    protected function additionals(MutationDataProviderInterface $mutationDataProvider): void
    {
        App::doAction('pop_volunteer', $mutationDataProvider);
    }

    protected function doExecute(MutationDataProviderInterface $mutationDataProvider)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $post_title = $this->getCustomPostTypeAPI()->getTitle($mutationDataProvider->getValue('target-id'));
        $subject = sprintf(
            $this->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            sprintf(
                $this->__('%s applied to volunteer for %s', 'pop-genericforms'),
                $mutationDataProvider->getValue('name'),
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
                $mutationDataProvider->getValue('name'),
                $this->getCustomPostTypeAPI()->getPermalink($mutationDataProvider->getValue('target-id')),
                $post_title
            )
        ) . sprintf(
            $placeholder,
            $this->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $mutationDataProvider->getValue('email')
            )
        ) . sprintf(
            $placeholder,
            $this->__('Phone', 'pop-genericforms'),
            $mutationDataProvider->getValue('phone')
        ) . sprintf(
            $placeholder,
            $this->__('Why volunteer', 'pop-genericforms'),
            $mutationDataProvider->getValue('whyvolunteer')
        );

        return PoP_EmailSender_Utils::sendemailToUsersFromPost(array($mutationDataProvider->getValue('target-id')), $subject, $msg);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(MutationDataProviderInterface $mutationDataProvider): mixed
    {
        $result = $this->doExecute($mutationDataProvider);

        // Allow for additional operations
        $this->additionals($mutationDataProvider);

        return $result;
    }
}

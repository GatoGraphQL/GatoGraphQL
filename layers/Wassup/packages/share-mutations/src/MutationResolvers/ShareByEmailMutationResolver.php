<?php

declare(strict_types=1);

namespace PoPSitesWassup\ShareMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP_EmailSender_Utils;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class ShareByEmailMutationResolver extends AbstractMutationResolver
{
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

        if (empty($fieldDataProvider->get('target-url'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('The shared-page URL cannot be empty.', 'pop-genericforms');
        }

        if (empty($fieldDataProvider->get('target-title'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('The shared-page title cannot be empty.', 'pop-genericforms');
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals(FieldDataAccessorInterface $fieldDataProvider): void
    {
        App::doAction('pop_sharebyemail', $fieldDataProvider);
    }

    protected function doExecute(FieldDataAccessorInterface $fieldDataProvider)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $subject = sprintf(
            $this->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            sprintf(
                $this->__('%s is sharing with you: %s', 'pop-genericforms'),
                $fieldDataProvider->get('name'),
                $fieldDataProvider->get('target-title')
            )
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            sprintf(
                $this->__('%s is sharing with you: <a href="%s">%s</a>', 'pop-genericforms'),
                $fieldDataProvider->get('name'),
                $fieldDataProvider->get('target-url'),
                $fieldDataProvider->get('target-title')
            )
        ) . ($fieldDataProvider->get('message') ? sprintf(
            $placeholder,
            $this->__('Additional message', 'pop-genericforms'),
            $fieldDataProvider->get('message')
        ) : '');

        return PoP_EmailSender_Utils::sendEmail($fieldDataProvider->get('email'), $subject, $msg);
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

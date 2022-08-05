<?php

declare(strict_types=1);

namespace PoPSitesWassup\ShareMutations\MutationResolvers;

use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP_EmailSender_Utils;

class ShareByEmailMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (empty($fieldDataAccessor->getValue('name'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->__('Your name cannot be empty.', 'pop-genericforms');
        }

        if (empty($fieldDataAccessor->getValue('email'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($fieldDataAccessor->getValue('email'), FILTER_VALIDATE_EMAIL)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->__('Email format is incorrect.', 'pop-genericforms');
        }

        if (empty($fieldDataAccessor->getValue('target-url'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->__('The shared-page URL cannot be empty.', 'pop-genericforms');
        }

        if (empty($fieldDataAccessor->getValue('target-title'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->__('The shared-page title cannot be empty.', 'pop-genericforms');
        }
    }

    /**
     * Function to override
     */
    protected function additionals(FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('pop_sharebyemail', $fieldDataAccessor);
    }

    protected function doExecute(FieldDataAccessorInterface $fieldDataAccessor)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $subject = sprintf(
            $this->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            sprintf(
                $this->__('%s is sharing with you: %s', 'pop-genericforms'),
                $fieldDataAccessor->getValue('name'),
                $fieldDataAccessor->getValue('target-title')
            )
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            sprintf(
                $this->__('%s is sharing with you: <a href="%s">%s</a>', 'pop-genericforms'),
                $fieldDataAccessor->getValue('name'),
                $fieldDataAccessor->getValue('target-url'),
                $fieldDataAccessor->getValue('target-title')
            )
        ) . ($fieldDataAccessor->getValue('message') ? sprintf(
            $placeholder,
            $this->__('Additional message', 'pop-genericforms'),
            $fieldDataAccessor->getValue('message')
        ) : '');

        return PoP_EmailSender_Utils::sendEmail($fieldDataAccessor->getValue('email'), $subject, $msg);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $result = $this->doExecute($fieldDataAccessor);

        // Allow for additional operations
        $this->additionals($fieldDataAccessor);

        return $result;
    }
}

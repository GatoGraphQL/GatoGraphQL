<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUsMutations\MutationResolvers;

use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP_EmailSender_Utils;

class ContactUsMutationResolver extends AbstractMutationResolver
{
    public function validate(
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
        if (empty($fieldDataAccessor->getValue('message'))) {
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
            $errors[] = $this->__('Message cannot be empty.', 'pop-genericforms');
        }
    }

    /**
     * Function to override
     */
    protected function additionals(FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('pop_contactus', $fieldDataAccessor);
    }

    protected function doExecute(FieldDataAccessorInterface $fieldDataAccessor)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $to = PoP_EmailSender_Utils::getAdminNotificationsEmail();
        $subject = sprintf(
            $this->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            $this->__('Contact us', 'pop-genericforms')
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            $this->__('New contact us submission', 'pop-genericforms')
        ) . sprintf(
            $placeholder,
            $this->__('Name', 'pop-genericforms'),
            $fieldDataAccessor->getValue('name')
        ) . sprintf(
            $placeholder,
            $this->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $fieldDataAccessor->getValue('email')
            )
        ) . sprintf(
            $placeholder,
            $this->__('Subject', 'pop-genericforms'),
            $fieldDataAccessor->getValue('subject')
        ) . sprintf(
            $placeholder,
            $this->__('Message', 'pop-genericforms'),
            $fieldDataAccessor->getValue('message')
        );

        return PoP_EmailSender_Utils::sendEmail($to, $subject, $msg);
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

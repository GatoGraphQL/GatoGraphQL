<?php

declare(strict_types=1);

namespace PoPSitesWassup\NewsletterMutations\MutationResolvers;

use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP_EmailSender_Utils;

class NewsletterSubscriptionMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
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
    }

    /**
     * Function to override
     */
    protected function additionals(FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('pop_subscribetonewsletter', $fieldDataAccessor);
    }

    protected function doExecute(FieldDataAccessorInterface $fieldDataAccessor)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $to = PoP_EmailSender_Utils::getAdminNotificationsEmail();
        $subject = sprintf(
            $this->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            $this->__('Newsletter Subscription', 'pop-genericforms')
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            $this->__('User subscribed to newsletter', 'pop-genericforms')
        ) . sprintf(
            $placeholder,
            $this->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $fieldDataAccessor->getValue('email')
            )
        ) . sprintf(
            $placeholder,
            $this->__('Name', 'pop-genericforms'),
            $fieldDataAccessor->getValue('name')
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

<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP_Notifications_API;

abstract class AbstractMarkAsReadOrUnreadNotificationMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $histid = $fieldDataAccessor->getValue('histid');
        if (!$histid) {
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
            $errors[] = $this->__('This URL is incorrect.', 'pop-notifications');
        } else {
            // $notification = AAL_Main::instance()->api->getNotification($histid);
            $notification = PoP_Notifications_API::getNotification($histid);
            if (!$notification) {
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
                $errors[] = $this->__('This notification does not exist.', 'pop-notifications');
            }
        }
    }

    protected function additionals(string|int $histid, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('GD_NotificationMarkAsReadUnread:additionals', $histid, $fieldDataAccessor);
    }

    abstract protected function getStatus();

    protected function setStatus(FieldDataAccessorInterface $fieldDataAccessor)
    {
        // return AAL_Main::instance()->api->setStatus($fieldDataAccessor->getValue('histid'), $fieldDataAccessor->getValue('user_id'), $this->getStatus());
        return PoP_Notifications_API::setStatus($fieldDataAccessor->getValue('histid'), $fieldDataAccessor->getValue('user_id'), $this->getStatus());
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $hist_ids = $this->setStatus($fieldDataAccessor);
        $this->additionals($fieldDataAccessor->getValue('histid'), $fieldDataAccessor);

        return $hist_ids; //$fieldDataAccessor->getValue('histid');
    }
}

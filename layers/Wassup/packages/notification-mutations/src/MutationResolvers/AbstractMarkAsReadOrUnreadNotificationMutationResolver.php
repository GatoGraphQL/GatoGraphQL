<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP_Notifications_API;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

abstract class AbstractMarkAsReadOrUnreadNotificationMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $errors = [];
        $histid = $fieldDataAccessor->getValue('histid');
        if (!$histid) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('This URL is incorrect.', 'pop-notifications');
        } else {
            // $notification = AAL_Main::instance()->api->getNotification($histid);
            $notification = PoP_Notifications_API::getNotification($histid);
            if (!$notification) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = $this->__('This notification does not exist.', 'pop-notifications');
            }
        }
        return $errors;
    }

    protected function additionals($histid, FieldDataAccessorInterface $fieldDataAccessor): void
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
    public function executeMutation(FieldDataAccessorInterface $fieldDataAccessor): mixed
    {
        $hist_ids = $this->setStatus($fieldDataAccessor);
        $this->additionals($fieldDataAccessor->getValue('histid'), $fieldDataAccessor);

        return $hist_ids; //$fieldDataAccessor->getValue('histid');
    }
}

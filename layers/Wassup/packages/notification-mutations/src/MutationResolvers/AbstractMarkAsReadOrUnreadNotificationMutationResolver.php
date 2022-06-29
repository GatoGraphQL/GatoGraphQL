<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_Notifications_API;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

abstract class AbstractMarkAsReadOrUnreadNotificationMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(WithArgumentsInterface $withArgumentsAST): array
    {
        $errors = [];
        $histid = $withArgumentsAST->getArgumentValue('histid');
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

    protected function additionals($histid, $withArgumentsAST): void
    {
        App::doAction('GD_NotificationMarkAsReadUnread:additionals', $histid, $withArgumentsAST);
    }

    abstract protected function getStatus();

    protected function setStatus($withArgumentsAST)
    {
        // return AAL_Main::instance()->api->setStatus($withArgumentsAST->getArgumentValue('histid'), $withArgumentsAST->getArgumentValue('user_id'), $this->getStatus());
        return PoP_Notifications_API::setStatus($withArgumentsAST->getArgumentValue('histid'), $withArgumentsAST->getArgumentValue('user_id'), $this->getStatus());
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(WithArgumentsInterface $withArgumentsAST): mixed
    {
        $hist_ids = $this->setStatus($withArgumentsAST);
        $this->additionals($withArgumentsAST->getArgumentValue('histid'), $withArgumentsAST);

        return $hist_ids; //$withArgumentsAST->getArgumentValue('histid');
    }
}

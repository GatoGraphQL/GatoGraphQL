<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_Notifications_API;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class MarkAllAsReadNotificationMutationResolver extends AbstractMutationResolver
{
    protected function additionals(WithArgumentsInterface $withArgumentsAST): void
    {
        App::doAction('GD_NotificationMarkAllAsRead:additionals', $withArgumentsAST);
    }

    protected function markAllAsRead(WithArgumentsInterface $withArgumentsAST)
    {
        // return AAL_Main::instance()->api->setStatusMultipleNotifications($withArgumentsAST->getArgumentValue('user_id'), AAL_POP_STATUS_READ);
        return PoP_Notifications_API::setStatusMultipleNotifications($withArgumentsAST->getArgumentValue('user_id'), \AAL_POP_STATUS_READ);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(WithArgumentsInterface $withArgumentsAST): mixed
    {
        $hist_ids = $this->markAllAsRead($withArgumentsAST);
        $this->additionals($withArgumentsAST);

        return $hist_ids;
    }
}

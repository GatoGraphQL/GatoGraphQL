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
    protected function additionals(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): void
    {
        App::doAction('GD_NotificationMarkAllAsRead:additionals', $mutationDataProvider);
    }

    protected function markAllAsRead(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider)
    {
        // return AAL_Main::instance()->api->setStatusMultipleNotifications($mutationDataProvider->getArgumentValue('user_id'), AAL_POP_STATUS_READ);
        return PoP_Notifications_API::setStatusMultipleNotifications($mutationDataProvider->getArgumentValue('user_id'), \AAL_POP_STATUS_READ);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): mixed
    {
        $hist_ids = $this->markAllAsRead($mutationDataProvider);
        $this->additionals($mutationDataProvider);

        return $hist_ids;
    }
}

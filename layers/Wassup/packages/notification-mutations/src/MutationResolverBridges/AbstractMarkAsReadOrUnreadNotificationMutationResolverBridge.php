<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

abstract class AbstractMarkAsReadOrUnreadNotificationMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('histid', App::query($this->getRequestKey()), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('user_id', App::getState('current-user-id'), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
    }

    protected function getRequestKey()
    {
        return 'nid';
    }
}

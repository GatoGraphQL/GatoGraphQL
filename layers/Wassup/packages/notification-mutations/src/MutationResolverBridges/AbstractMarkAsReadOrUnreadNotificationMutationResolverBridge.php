<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\App;

abstract class AbstractMarkAsReadOrUnreadNotificationMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('histid', new Literal(App::query($this->getRequestKey()), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('user_id', new Literal(App::getState('current-user-id'), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
    }

    protected function getRequestKey()
    {
        return 'nid';
    }
}

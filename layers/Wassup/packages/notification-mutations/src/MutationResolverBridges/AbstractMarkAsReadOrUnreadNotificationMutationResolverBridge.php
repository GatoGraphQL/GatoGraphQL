<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\Root\App;

abstract class AbstractMarkAsReadOrUnreadNotificationMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function addArgumentsForMutation(FieldInterface $mutationField): void
    {
        $mutationField->addArgument(new Argument('histid', new Literal(App::query($this->getRequestKey()), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('user_id', new Literal(App::getState('current-user-id'), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
    }

    protected function getRequestKey()
    {
        return 'nid';
    }
}

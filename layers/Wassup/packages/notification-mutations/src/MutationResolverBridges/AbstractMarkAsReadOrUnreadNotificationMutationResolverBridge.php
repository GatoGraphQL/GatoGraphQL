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
    public function fillMutationDataProvider(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): void
    {
        $mutationDataProvider->add('histid', App::query($this->getRequestKey()));
        $mutationDataProvider->add('user_id', App::getState('current-user-id'));
    }

    protected function getRequestKey()
    {
        return 'nid';
    }
}

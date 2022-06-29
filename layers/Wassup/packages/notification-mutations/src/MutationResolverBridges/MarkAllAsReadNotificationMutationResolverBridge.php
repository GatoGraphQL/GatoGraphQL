<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\App;
use PoPSitesWassup\NotificationMutations\MutationResolvers\MarkAllAsReadNotificationMutationResolver;

class MarkAllAsReadNotificationMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?MarkAllAsReadNotificationMutationResolver $markAllAsReadNotificationMutationResolver = null;

    final public function setMarkAllAsReadNotificationMutationResolver(MarkAllAsReadNotificationMutationResolver $markAllAsReadNotificationMutationResolver): void
    {
        $this->markAllAsReadNotificationMutationResolver = $markAllAsReadNotificationMutationResolver;
    }
    final protected function getMarkAllAsReadNotificationMutationResolver(): MarkAllAsReadNotificationMutationResolver
    {
        return $this->markAllAsReadNotificationMutationResolver ??= $this->instanceManager->getInstance(MarkAllAsReadNotificationMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getMarkAllAsReadNotificationMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('user_id', new Literal(App::getState('current-user-id'), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
    }
}

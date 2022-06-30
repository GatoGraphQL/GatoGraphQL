<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
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

    public function addArgumentsForMutation(FieldInterface $mutationField): void
    {
        $mutationField->addArgument(new Argument('user_id', new Literal(App::getState('current-user-id'), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
    }
}

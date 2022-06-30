<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

abstract class AbstractSystemComponentMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function addArgumentsForMutation(FieldInterface $mutationField): void
    {
    }
}

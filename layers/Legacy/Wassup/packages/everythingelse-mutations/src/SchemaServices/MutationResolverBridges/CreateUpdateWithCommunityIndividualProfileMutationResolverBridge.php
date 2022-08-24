<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateWithCommunityIndividualProfileMutationResolver;

class CreateUpdateWithCommunityIndividualProfileMutationResolverBridge extends CreateUpdateIndividualProfileMutationResolverBridge
{
    private ?CreateUpdateWithCommunityIndividualProfileMutationResolver $createUpdateWithCommunityIndividualProfileMutationResolver = null;
    
    final public function setCreateUpdateWithCommunityIndividualProfileMutationResolver(CreateUpdateWithCommunityIndividualProfileMutationResolver $createUpdateWithCommunityIndividualProfileMutationResolver): void
    {
        $this->createUpdateWithCommunityIndividualProfileMutationResolver = $createUpdateWithCommunityIndividualProfileMutationResolver;
    }
    final protected function getCreateUpdateWithCommunityIndividualProfileMutationResolver(): CreateUpdateWithCommunityIndividualProfileMutationResolver
    {
        /** @var CreateUpdateWithCommunityIndividualProfileMutationResolver */
        return $this->createUpdateWithCommunityIndividualProfileMutationResolver ??= $this->instanceManager->getInstance(CreateUpdateWithCommunityIndividualProfileMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateUpdateWithCommunityIndividualProfileMutationResolver();
    }
}

<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoPSitesWassup\Services\EverythingElseMutations\MutationResolvers\CreateUpdateWithCommunityIndividualProfileMutationResolver;

class CreateUpdateWithCommunityIndividualProfileMutationResolverBridge extends CreateUpdateIndividualProfileMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return CreateUpdateWithCommunityIndividualProfileMutationResolver::class;
    }
}

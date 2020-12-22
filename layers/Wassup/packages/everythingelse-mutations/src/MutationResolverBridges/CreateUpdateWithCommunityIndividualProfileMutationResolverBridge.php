<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\MutationResolverBridges;

use PoPSitesWassup\EverythingElseMutations\MutationResolvers\CreateUpdateWithCommunityIndividualProfileMutationResolver;

class CreateUpdateWithCommunityIndividualProfileMutationResolverBridge extends CreateUpdateIndividualProfileMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return CreateUpdateWithCommunityIndividualProfileMutationResolver::class;
    }
}


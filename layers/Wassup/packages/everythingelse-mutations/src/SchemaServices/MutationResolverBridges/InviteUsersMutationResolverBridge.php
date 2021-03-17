<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoPSitesWassup\Services\EverythingElseMutations\MutationResolvers\InviteUsersMutationResolver;

class InviteUsersMutationResolverBridge extends AbstractEmailInviteMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return InviteUsersMutationResolver::class;
    }
}

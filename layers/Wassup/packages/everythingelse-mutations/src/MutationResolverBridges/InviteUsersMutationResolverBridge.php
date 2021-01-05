<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\MutationResolverBridges;

use PoPSitesWassup\EverythingElseMutations\MutationResolvers\InviteUsersMutationResolver;

class InviteUsersMutationResolverBridge extends AbstractEmailInviteMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return InviteUsersMutationResolver::class;
    }
}

<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\MutationResolverBridges;

use PoPSitesWassup\EverythingElseMutations\MutationResolvers\InviteMembersMutationResolver;

class InviteMembersMutationResolverBridge extends AbstractEmailInviteMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return InviteMembersMutationResolver::class;
    }
}


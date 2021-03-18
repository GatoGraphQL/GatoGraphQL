<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\InviteMembersMutationResolver;

class InviteMembersMutationResolverBridge extends AbstractEmailInviteMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return InviteMembersMutationResolver::class;
    }
}

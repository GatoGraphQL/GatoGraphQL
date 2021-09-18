<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\InviteUsersMutationResolver;

class InviteUsersMutationResolverBridge extends AbstractEmailInviteMutationResolverBridge
{
    public function getMutationResolver(): \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
    {
        return InviteUsersMutationResolver::class;
    }
}

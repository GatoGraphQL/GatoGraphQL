<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\InviteMembersMutationResolver;

class InviteMembersMutationResolverBridge extends AbstractEmailInviteMutationResolverBridge
{
    private ?InviteMembersMutationResolver $inviteMembersMutationResolver = null;
    
    final public function setInviteMembersMutationResolver(InviteMembersMutationResolver $inviteMembersMutationResolver): void
    {
        $this->inviteMembersMutationResolver = $inviteMembersMutationResolver;
    }
    final protected function getInviteMembersMutationResolver(): InviteMembersMutationResolver
    {
        /** @var InviteMembersMutationResolver */
        return $this->inviteMembersMutationResolver ??= $this->instanceManager->getInstance(InviteMembersMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getInviteMembersMutationResolver();
    }
}

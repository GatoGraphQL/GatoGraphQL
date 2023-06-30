<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\InviteUsersMutationResolver;

class InviteUsersMutationResolverBridge extends AbstractEmailInviteMutationResolverBridge
{
    private ?InviteUsersMutationResolver $inviteUsersMutationResolver = null;
    
    final public function setInviteUsersMutationResolver(InviteUsersMutationResolver $inviteUsersMutationResolver): void
    {
        $this->inviteUsersMutationResolver = $inviteUsersMutationResolver;
    }
    final protected function getInviteUsersMutationResolver(): InviteUsersMutationResolver
    {
        if ($this->inviteUsersMutationResolver === null) {
            /** @var InviteUsersMutationResolver */
            $inviteUsersMutationResolver = $this->instanceManager->getInstance(InviteUsersMutationResolver::class);
            $this->inviteUsersMutationResolver = $inviteUsersMutationResolver;
        }
        return $this->inviteUsersMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getInviteUsersMutationResolver();
    }
}

<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\InviteMembersMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class InviteMembersMutationResolverBridge extends AbstractEmailInviteMutationResolverBridge
{
    private ?InviteMembersMutationResolver $inviteMembersMutationResolver = null;
    
    public function setInviteMembersMutationResolver(InviteMembersMutationResolver $inviteMembersMutationResolver): void
    {
        $this->inviteMembersMutationResolver = $inviteMembersMutationResolver;
    }
    protected function getInviteMembersMutationResolver(): InviteMembersMutationResolver
    {
        return $this->inviteMembersMutationResolver ??= $this->instanceManager->getInstance(InviteMembersMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getInviteMembersMutationResolver();
    }
}

<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\InviteUsersMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class InviteUsersMutationResolverBridge extends AbstractEmailInviteMutationResolverBridge
{
    protected ?InviteUsersMutationResolver $inviteUsersMutationResolver = null;
    
    public function setInviteUsersMutationResolver(InviteUsersMutationResolver $inviteUsersMutationResolver): void
    {
        $this->inviteUsersMutationResolver = $inviteUsersMutationResolver;
    }
    protected function getInviteUsersMutationResolver(): InviteUsersMutationResolver
    {
        return $this->inviteUsersMutationResolver ??= $this->instanceManager->getInstance(InviteUsersMutationResolver::class);
    }

    //#[Required]
    final public function autowireInviteUsersMutationResolverBridge(
        InviteUsersMutationResolver $inviteUsersMutationResolver,
    ): void {
        $this->inviteUsersMutationResolver = $inviteUsersMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getInviteUsersMutationResolver();
    }
}

<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\InviteUsersMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class InviteUsersMutationResolverBridge extends AbstractEmailInviteMutationResolverBridge
{
    private ?InviteUsersMutationResolver $inviteUsersMutationResolver = null;
    
    public function setInviteUsersMutationResolver(InviteUsersMutationResolver $inviteUsersMutationResolver): void
    {
        $this->inviteUsersMutationResolver = $inviteUsersMutationResolver;
    }
    protected function getInviteUsersMutationResolver(): InviteUsersMutationResolver
    {
        return $this->inviteUsersMutationResolver ??= $this->getInstanceManager()->getInstance(InviteUsersMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getInviteUsersMutationResolver();
    }
}

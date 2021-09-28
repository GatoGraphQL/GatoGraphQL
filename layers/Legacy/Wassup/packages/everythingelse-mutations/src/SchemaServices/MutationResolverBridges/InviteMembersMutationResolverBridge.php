<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\InviteMembersMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class InviteMembersMutationResolverBridge extends AbstractEmailInviteMutationResolverBridge
{
    protected InviteMembersMutationResolver $inviteMembersMutationResolver;
    
    #[Required]
    public function autowireInviteMembersMutationResolverBridge(
        InviteMembersMutationResolver $inviteMembersMutationResolver,
    ): void {
        $this->inviteMembersMutationResolver = $inviteMembersMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->inviteMembersMutationResolver;
    }
}

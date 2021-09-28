<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\InviteUsersMutationResolver;

class InviteUsersMutationResolverBridge extends AbstractEmailInviteMutationResolverBridge
{
    protected InviteUsersMutationResolver $inviteUsersMutationResolver;
    public function __construct(
        InviteUsersMutationResolver $inviteUsersMutationResolver,
    ) {
        $this->inviteUsersMutationResolver = $inviteUsersMutationResolver;
        }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->inviteUsersMutationResolver;
    }
}

<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateIndividualProfileMutationResolver;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateProfileMutationResolver;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateWithCommunityIndividualProfileMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CreateUpdateWithCommunityIndividualProfileMutationResolverBridge extends CreateUpdateIndividualProfileMutationResolverBridge
{
    protected CreateUpdateWithCommunityIndividualProfileMutationResolver $createUpdateWithCommunityIndividualProfileMutationResolver;
    
    #[Required]
    public function autowireCreateUpdateWithCommunityIndividualProfileMutationResolverBridge(
        CreateUpdateWithCommunityIndividualProfileMutationResolver $createUpdateWithCommunityIndividualProfileMutationResolver,
    ) {
        $this->createUpdateWithCommunityIndividualProfileMutationResolver = $createUpdateWithCommunityIndividualProfileMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->createUpdateWithCommunityIndividualProfileMutationResolver;
    }
}

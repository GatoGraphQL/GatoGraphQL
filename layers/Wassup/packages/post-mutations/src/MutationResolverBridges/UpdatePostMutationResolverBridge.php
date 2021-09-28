<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolverBridges;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostMutations\MutationResolvers\UpdatePostMutationResolver;

class UpdatePostMutationResolverBridge extends AbstractCreateUpdatePostMutationResolverBridge
{
    protected UpdatePostMutationResolver $updatePostMutationResolver;
    public function __construct(
        UpdatePostMutationResolver $updatePostMutationResolver,
    ) {
        $this->updatePostMutationResolver = $updatePostMutationResolver;
        }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->updatePostMutationResolver;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}

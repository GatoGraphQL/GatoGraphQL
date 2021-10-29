<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostMutations\MutationResolvers\UpdatePostMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UpdatePostMutationResolverBridge extends AbstractCreateUpdatePostMutationResolverBridge
{
    private ?UpdatePostMutationResolver $updatePostMutationResolver = null;

    public function setUpdatePostMutationResolver(UpdatePostMutationResolver $updatePostMutationResolver): void
    {
        $this->updatePostMutationResolver = $updatePostMutationResolver;
    }
    protected function getUpdatePostMutationResolver(): UpdatePostMutationResolver
    {
        return $this->updatePostMutationResolver ??= $this->instanceManager->getInstance(UpdatePostMutationResolver::class);
    }

    //#[Required]
    final public function autowireUpdatePostMutationResolverBridge(
        UpdatePostMutationResolver $updatePostMutationResolver,
    ): void {
        $this->updatePostMutationResolver = $updatePostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdatePostMutationResolver();
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}

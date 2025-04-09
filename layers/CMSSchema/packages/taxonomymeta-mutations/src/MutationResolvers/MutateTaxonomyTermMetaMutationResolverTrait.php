<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\MutationResolvers\MutateEntityMetaMutationResolverTrait;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;

trait MutateTaxonomyTermMetaMutationResolverTrait
{
    use MutateEntityMetaMutationResolverTrait;

    abstract protected function getTaxonomyMetaTypeAPI(): TaxonomyMetaTypeAPIInterface;

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getTaxonomyMetaTypeAPI();
    }

    protected function doesMetaEntryExist(
        string|int $entityID,
        string $key,
    ): bool {
        return $this->getTaxonomyMetaTypeAPI()->getTaxonomyTermMeta($entityID, $key, true) !== null;
    }

    protected function doesMetaEntryWithValueExist(
        string|int $entityID,
        string $key,
        mixed $value,
    ): bool {
        return in_array($value, $this->getTaxonomyMetaTypeAPI()->getTaxonomyTermMeta($entityID, $key, false));
    }

    protected function doesMetaEntryHaveValue(
        string|int $entityID,
        string $key,
        mixed $value,
    ): bool {
        $existingValue = $this->getTaxonomyMetaTypeAPI()->getTaxonomyTermMeta($entityID, $key, false);
        return $existingValue === [$value];
    }
}

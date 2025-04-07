<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\MutationResolvers\MutateEntityMetaMutationResolverTrait;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;

trait MutateCustomPostMetaMutationResolverTrait
{
    use MutateEntityMetaMutationResolverTrait;

    abstract protected function getCustomPostMetaTypeAPI(): CustomPostMetaTypeAPIInterface;

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getCustomPostMetaTypeAPI();
    }

    protected function doesMetaEntryExist(
        string|int $entityID,
        string $key,
    ): bool {
        return $this->getCustomPostMetaTypeAPI()->getCustomPostMeta($entityID, $key, true) !== null;
    }

    protected function doesMetaEntryWithValueExist(
        string|int $entityID,
        string $key,
        mixed $value,
    ): bool {
        return in_array($value, $this->getCustomPostMetaTypeAPI()->getCustomPostMeta($entityID, $key, false));
    }

    protected function doesMetaEntryHaveValue(
        string|int $entityID,
        string $key,
        mixed $value,
    ): bool {
        $existingValue = $this->getCustomPostMetaTypeAPI()->getCustomPostMeta($entityID, $key, false);
        return $existingValue === [$value];
    }
}

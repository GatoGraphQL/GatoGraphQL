<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\MutationResolvers\MutateTermMetaMutationResolverTrait;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;

trait MutateCustomPostMetaMutationResolverTrait
{
    use MutateTermMetaMutationResolverTrait;

    abstract protected function getCustomPostMetaTypeAPI(): CustomPostMetaTypeAPIInterface;

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getCustomPostMetaTypeAPI();
    }

    protected function doesMetaEntryExist(
        string|int $termID,
        string $key,
    ): bool {
        return $this->getCustomPostMetaTypeAPI()->getCustomPostMeta($termID, $key, true) !== null;
    }

    protected function doesMetaEntryWithValueExist(
        string|int $termID,
        string $key,
        mixed $value,
    ): bool {
        return in_array($value, $this->getCustomPostMetaTypeAPI()->getCustomPostMeta($termID, $key, false));
    }
}

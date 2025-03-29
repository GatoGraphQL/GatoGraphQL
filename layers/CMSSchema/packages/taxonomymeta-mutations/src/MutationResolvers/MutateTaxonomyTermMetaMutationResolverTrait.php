<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\MutationResolvers\MutateTermMetaMutationResolverTrait;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;

trait MutateTaxonomyTermMetaMutationResolverTrait
{
    use MutateTermMetaMutationResolverTrait;
    
    abstract protected function getTaxonomyMetaTypeAPI(): TaxonomyMetaTypeAPIInterface;

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getTaxonomyMetaTypeAPI();
    }

    protected function doesMetaEntryExist(
        string|int $termID,
        string $key,
    ): bool {
        return $this->getTaxonomyMetaTypeAPI()->getTaxonomyTermMeta($termID, $key, true) !== null;
    }
}

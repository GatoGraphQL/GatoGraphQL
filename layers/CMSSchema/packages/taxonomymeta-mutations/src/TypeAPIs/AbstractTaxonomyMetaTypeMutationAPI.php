<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs;

use PoPCMSSchema\MetaMutations\TypeAPIs\AbstractEntityMetaTypeMutationAPI;
use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;

abstract class AbstractTaxonomyMetaTypeMutationAPI extends AbstractEntityMetaTypeMutationAPI implements TaxonomyMetaTypeMutationAPIInterface
{
    /**
     * @phpstan-return class-string<EntityMetaCRUDMutationException>
     */
    protected function getEntityMetaCRUDMutationExceptionClass(): string
    {
        return TaxonomyTermMetaCRUDMutationException::class;
    }
}

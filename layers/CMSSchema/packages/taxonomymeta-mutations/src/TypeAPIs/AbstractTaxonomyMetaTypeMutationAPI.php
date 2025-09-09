<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs;

use PoPCMSSchema\MetaMutations\Exception\EntityMetaCRUDMutationException;
use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;
use PoP\Root\Services\AbstractBasicService;

abstract class AbstractTaxonomyMetaTypeMutationAPI extends AbstractBasicService implements TaxonomyMetaTypeMutationAPIInterface
{
    /**
     * @phpstan-return class-string<EntityMetaCRUDMutationException>
     */
    protected function getEntityMetaCRUDMutationExceptionClass(): string
    {
        return TaxonomyTermMetaCRUDMutationException::class;
    }

    /**
     * @param array<string,mixed[]|null> $entries
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error
     */
    public function setTaxonomyTermMeta(
        string|int $taxonomyTermID,
        array $entries,
    ): void {
        $this->setEntityMeta(
            $taxonomyTermID,
            $entries,
        );
    }

    /**
     * @return int The term_id of the newly created term
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error
     */
    public function addTaxonomyTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int {
        return $this->addEntityMeta(
            $taxonomyTermID,
            $key,
            $value,
            $single,
        );
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTaxonomyTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): string|int|bool {
        return $this->updateEntityMeta(
            $taxonomyTermID,
            $key,
            $value,
            $prevValue,
        );
    }

    /**
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy does not exist)
     */
    public function deleteTaxonomyTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value = null,
    ): void {
        $this->deleteEntityMeta(
            $taxonomyTermID,
            $key,
            $value,
        );
    }
}

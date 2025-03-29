<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\SchemaCommonsWP\TypeAPIs\TypeMutationAPITrait;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\AbstractTaxonomyMetaTypeMutationAPI;
use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;
use WP_Error;

use function add_term_meta;
use function delete_term_meta;
use function update_term_meta;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class TaxonomyMetaTypeMutationAPI extends AbstractTaxonomyMetaTypeMutationAPI
{
    use TypeMutationAPITrait;

    /**
     * @param array<string,mixed[]|null> $entries
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error
     */
    public function setTaxonomyTermMeta(
        string|int $taxonomyTermID,
        array $entries,
    ): void {
        foreach ($entries as $key => $values) {
            if ($values === null) {
                $this->deleteTaxonomyTermMeta($taxonomyTermID, $key);
                continue;
            }

            $numberItems = count($values);
            if ($numberItems === 0) {
                continue;
            }

            /**
             * If there are 2 or more items, then use `add` to add them.
             * If there is only 1 item, then use `update` to update it.
             */
            if ($numberItems === 1) {
                $value = $values[0];
                if ($value === null) {
                    $this->deleteTaxonomyTermMeta($taxonomyTermID, $key);
                    continue;
                }
                $this->updateTaxonomyTermMeta($taxonomyTermID, $key, $value);
                continue;
            }

            // $numberItems > 1
            $this->deleteTaxonomyTermMeta($taxonomyTermID, $key);
            foreach ($values as $value) {
                $this->addTaxonomyTermMeta($taxonomyTermID, $key, $value, false);
            }
        }
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
        $result = add_term_meta((int) $taxonomyTermID, $key, $value, $single);
        $this->handleMaybeError($result);
        return $result;
    }

    protected function handleMaybeError(
        int|bool|WP_Error $result,
    ): void {
        if ($result === false) {
            throw $this->getTaxonomyTermMetaCRUDMutationException(\__('Error adding term meta', 'taxonomymeta-mutations-wp'));
        }
        if ($result instanceof WP_Error) {
            /** @var WP_Error */
            $wpError = $result;
            throw $this->getTaxonomyTermMetaCRUDMutationException($wpError);
        }
    }

    protected function getTaxonomyTermMetaCRUDMutationException(WP_Error|string $error): TaxonomyTermMetaCRUDMutationException
    {
        $taxonomyTermMetaCRUDMutationExceptionClass = $this->getTaxonomyTermMetaCRUDMutationExceptionClass();
        if (is_string($error)) {
            return new $taxonomyTermMetaCRUDMutationExceptionClass($error);
        }
        /** @var WP_Error */
        $wpError = $error;
        return new $taxonomyTermMetaCRUDMutationExceptionClass(
            $wpError->get_error_message(),
            $wpError->get_error_code() ? $wpError->get_error_code() : null,
            $this->getWPErrorData($wpError),
        );
    }

    /**
     * @phpstan-return class-string<TaxonomyTermMetaCRUDMutationException>
     */
    protected function getTaxonomyTermMetaCRUDMutationExceptionClass(): string
    {
        return TaxonomyTermMetaCRUDMutationException::class;
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTaxonomyTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
    ): string|int|bool {
        $result = update_term_meta((int) $taxonomyTermID, $key, $value);
        $this->handleMaybeError($result);
        return $result;
    }

    /**
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy does not exist)
     */
    public function deleteTaxonomyTermMeta(
        string|int $taxonomyTermID,
        string $key,
    ): void {
        $result = delete_term_meta((int) $taxonomyTermID, $key);
        $this->handleMaybeError($result);
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;
use PoPCMSSchema\SchemaCommonsWP\TypeAPIs\TypeMutationAPITrait;
use PoP\Root\Services\AbstractBasicService;
use WP_Error;

use function add_term_meta;
use function delete_term_meta;
use function update_term_meta;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class TaxonomyMetaTypeMutationAPI extends AbstractBasicService implements TaxonomyMetaTypeMutationAPIInterface
{
    use TypeMutationAPITrait;

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
        int|false|WP_Error $result,
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
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    public function updateTaxonomyTermMeta(
        string|int $taxonomyTermID,
        string $key,
        mixed $value,
    ): int {
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

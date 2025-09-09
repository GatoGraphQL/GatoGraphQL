<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\MetaMutationsWP\TypeAPIs\EntityMetaTypeMutationAPITrait;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\AbstractTaxonomyMetaTypeMutationAPI;
use WP_Error;

use function add_term_meta;
use function delete_term_meta;
use function update_term_meta;

class TaxonomyMetaTypeMutationAPI extends AbstractTaxonomyMetaTypeMutationAPI
{
    use EntityMetaTypeMutationAPITrait;

    protected function executeAddEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int|false|WP_Error {
        return add_term_meta((int) $entityID, $key, $value, $single);
    }

    protected function executeUpdateEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): int|bool|WP_Error {
        return update_term_meta((int) $entityID, $key, $value, $prevValue ?? '');
    }

    protected function executeDeleteEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value = null,
    ): bool {
        return delete_term_meta((int) $entityID, $key, $value ?? '');
    }
}

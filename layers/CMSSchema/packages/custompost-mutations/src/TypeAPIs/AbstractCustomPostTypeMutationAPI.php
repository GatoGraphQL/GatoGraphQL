<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeAPIs;

use PoP\ComponentModel\App;
use PoP\Root\Services\BasicServiceTrait;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractCustomPostTypeMutationAPI implements CustomPostTypeMutationAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    use BasicServiceTrait;

    /**
     * @param array<string,mixed> $query
     * @return array<string,mixed> $query
     */
    protected function convertCustomPostsMutationQuery(array $query): array
    {
        return App::applyFilters(
            self::HOOK_QUERY,
            $query
        );
    }
}

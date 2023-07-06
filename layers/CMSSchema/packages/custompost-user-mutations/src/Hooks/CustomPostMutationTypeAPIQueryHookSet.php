<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostUserMutations\Hooks;

use PoPCMSSchema\CustomPostMutations\TypeAPIs\AbstractCustomPostTypeMutationAPI;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class CustomPostMutationTypeAPIQueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            AbstractCustomPostTypeMutationAPI::HOOK_QUERY,
            $this->convertCustomPostsMutationQuery(...)
        );
    }

    /**
     * @param array<string,mixed> $query
     * @return array<string,mixed> $query
     */
    public function convertCustomPostsMutationQuery(array $query): array
    {
        if (isset($query['author-id'])) {
            $query['author'] = $query['author-id'];
            unset($query['author-id']);
        }

        return $query;
    }
}

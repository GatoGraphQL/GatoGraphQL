<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Hooks;

use PoPCMSSchema\CustomPostMutationsWP\TypeAPIs\CustomPostTypeMutationAPI;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class CustomPostMutationTypeAPIQueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            CustomPostTypeMutationAPI::HOOK_QUERY,
            $this->convertCustomPostsMutationQuery(...)
        );
    }

    /**
     * @param array<string,mixed> $query
     * @return array<string,mixed> $query
     */
    public function convertCustomPostsMutationQuery(array $query): array
    {
        if (isset($query['menu-order'])) {
            $query['menu_order'] = $query['menu-order'];
            unset($query['menu-order']);
        }

        return $query;
    }
}

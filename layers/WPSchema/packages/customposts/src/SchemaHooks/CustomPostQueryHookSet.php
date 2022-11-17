<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\SchemaHooks;

use PoPCMSSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPI;
use PoPWPSchema\CustomPosts\Enums\CustomPostStatus;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class CustomPostQueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            CustomPostTypeAPI::HOOK_STATUS_QUERY_ARG_VALUE,
            $this->getStatusQueryArgValue(...)
        );
    }

    public function getStatusQueryArgValue(string $status): string
    {
        return match ($status) {
            CustomPostStatus::AUTO_DRAFT => 'auto-draft',
            default => $status,
        };
    }
}

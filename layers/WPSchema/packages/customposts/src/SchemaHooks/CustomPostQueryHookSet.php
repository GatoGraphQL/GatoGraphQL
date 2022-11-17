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
        /**
         * @todo "auto-draft" must be converted to enum value "auto_draft" on `Post.status`.
         *       Until then, this code is commented
         */
        // App::addFilter(
        //     CustomPostTypeAPI::HOOK_STATUS_QUERY_ARG_VALUE,
        //     $this->getStatusQueryArgValue(...)
        // );
    }

    /**
     * @todo "auto-draft" must be converted to enum value "auto_draft" on `Post.status`.
     *       Until then, this code is commented
     */
    // public function getStatusQueryArgValue(string $status): string
    // {
    //     return match ($status) {
    //         CustomPostStatus::AUTO_DRAFT => 'auto-draft',
    //         default => $status,
    //     };
    // }
}

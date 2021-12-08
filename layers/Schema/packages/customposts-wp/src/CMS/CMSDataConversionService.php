<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\CMS;

use PoP\ComponentModel\Services\BasicServiceTrait;
use PoPSchema\CustomPosts\Types\Status;

class CMSDataConversionService implements CMSDataConversionServiceInterface
{
    use BasicServiceTrait;

    public const HOOK_STATUS_QUERY_ARG_VALUE = __CLASS__ . ':status-query-arg-value';

    public function convertCustomPostStatusFromPoPToCMS(string $status): string
    {
        $status = match ($status) {
            Status::PUBLISHED => 'publish',
            Status::PENDING => 'pending',
            Status::DRAFT => 'draft',
            Status::TRASH => 'trash',
            default => $status,
        };
        return $this->getHooksAPI()->applyFilters(
            self::HOOK_STATUS_QUERY_ARG_VALUE,
            $status
        );
    }
}

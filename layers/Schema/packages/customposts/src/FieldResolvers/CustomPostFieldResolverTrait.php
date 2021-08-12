<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers;

use PoPSchema\CustomPosts\Types\Status;

trait CustomPostFieldResolverTrait
{
    /**
     * @return string[]
     */
    protected function getUnrestrictedFieldCustomPostTypes(): array
    {
        return [
            Status::PUBLISHED,
            Status::DRAFT,
            Status::PENDING,
            Status::TRASH,
        ];
    }
}

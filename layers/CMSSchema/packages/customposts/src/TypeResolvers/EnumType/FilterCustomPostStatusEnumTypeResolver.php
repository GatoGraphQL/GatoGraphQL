<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\EnumType;

/**
 * Enable to search custom posts by additional statuses, such as "any"
 */
class FilterCustomPostStatusEnumTypeResolver extends CustomPostStatusEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'FilterCustomPostStatusEnum';
    }
}

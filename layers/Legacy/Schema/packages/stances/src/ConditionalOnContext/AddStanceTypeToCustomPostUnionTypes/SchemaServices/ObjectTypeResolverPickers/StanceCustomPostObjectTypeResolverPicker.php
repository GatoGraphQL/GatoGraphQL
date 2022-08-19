<?php

declare(strict_types=1);

namespace PoPSchema\Stances\ConditionalOnContext\AddStanceTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\Stances\ObjectTypeResolverPickers\AbstractStanceObjectTypeResolverPicker;

class StanceCustomPostObjectTypeResolverPicker extends AbstractStanceObjectTypeResolverPicker
{
    /**
     * @return array<class-string<\PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}

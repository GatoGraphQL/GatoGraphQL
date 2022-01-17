<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

class CustomPostEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return CustomPostUnionTypeHelpers::getTargetObjectTypeResolverCustomPostTypes();
    }
}

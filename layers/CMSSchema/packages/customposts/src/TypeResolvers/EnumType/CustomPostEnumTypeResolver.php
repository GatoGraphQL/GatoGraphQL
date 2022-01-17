<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

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

<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\EnumType;

use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;
use PoPSchema\CustomPosts\Enums\CustomPostContentFormatEnum;

class CustomPostContentFormatEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostContentFormat';
    }
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            CustomPostContentFormatEnum::HTML,
            CustomPostContentFormatEnum::PLAIN_TEXT,
        ];
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Enums;

use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;

class CustomPostContentFormatEnum extends AbstractEnumTypeResolver
{
    public const HTML = 'HTML';
    public const PLAIN_TEXT = 'PLAIN_TEXT';

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
            self::HTML,
            self::PLAIN_TEXT,
        ];
    }
}

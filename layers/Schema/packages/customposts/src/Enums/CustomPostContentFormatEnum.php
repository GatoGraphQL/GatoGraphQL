<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;

class CustomPostContentFormatEnum extends AbstractEnum
{
    public const NAME = 'CustomPostContentFormat';

    public const HTML = 'HTML';
    public const PLAIN_TEXT = 'PLAIN_TEXT';

    protected function getEnumName(): string
    {
        return self::NAME;
    }
    public function getValues(): array
    {
        return [
            self::HTML,
            self::PLAIN_TEXT,
        ];
    }
}

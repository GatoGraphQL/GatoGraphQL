<?php

declare(strict_types=1);

namespace PoPSchema\Media\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;

class MediaDeviceEnum extends AbstractEnum
{
    public const MOBILE = 'MOBILE';
    public const DESKTOP = 'DESKTOP';
    public const AUTOMATIC = 'AUTOMATIC';

    protected function getEnumName(): string
    {
        return 'MediaDevice';
    }
    public function getValues(): array
    {
        return [
            self::MOBILE,
            self::DESKTOP,
            self::AUTOMATIC,
        ];
    }
}

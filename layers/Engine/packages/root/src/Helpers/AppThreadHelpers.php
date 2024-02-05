<?php

declare(strict_types=1);

namespace PoP\Root\Helpers;

class AppThreadHelpers
{
    /**
     * @param array<string,mixed> $context
     */
    public static function getUniqueID(?string $name, array $context): string
    {
        return \sprintf(
            '%s:%s',
            $name ?? '',
            json_encode($context)
        );
    }
}

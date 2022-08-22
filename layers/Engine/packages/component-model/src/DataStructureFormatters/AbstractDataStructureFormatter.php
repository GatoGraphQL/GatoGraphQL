<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructureFormatters;

use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractDataStructureFormatter implements DataStructureFormatterInterface
{
    use BasicServiceTrait;

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $data
     */
    public function getFormattedData(array $data): array
    {
        return $data;
    }
}

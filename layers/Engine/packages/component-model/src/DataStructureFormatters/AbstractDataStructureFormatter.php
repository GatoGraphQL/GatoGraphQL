<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructureFormatters;

use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractDataStructureFormatter implements DataStructureFormatterInterface
{
    use BasicServiceTrait;

    public function getFormattedData(array $data): array
    {
        return $data;
    }
}

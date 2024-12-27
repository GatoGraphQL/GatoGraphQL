<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructureFormatters;

use PoP\Root\Services\AbstractBasicService;

abstract class AbstractDataStructureFormatter extends AbstractBasicService implements DataStructureFormatterInterface
{
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $data
     */
    public function getFormattedData(array $data): array
    {
        return $data;
    }
}

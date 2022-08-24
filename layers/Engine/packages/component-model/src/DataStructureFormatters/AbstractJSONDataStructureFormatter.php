<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructureFormatters;

abstract class AbstractJSONDataStructureFormatter extends AbstractDataStructureFormatter
{
    public function getContentType(): string
    {
        return 'application/json';
    }

    /**
     * @param array<string,mixed> $data
     */
    public function getOutputContent(array &$data): string
    {
        return (string)json_encode($data, $this->getJsonEncodeType() ?? 0);
    }

    protected function getJsonEncodeType(): ?int
    {
        return null;
    }
}

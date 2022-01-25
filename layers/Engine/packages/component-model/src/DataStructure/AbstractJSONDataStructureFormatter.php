<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

abstract class AbstractJSONDataStructureFormatter extends AbstractDataStructureFormatter
{
    public function getContentType(): string
    {
        return 'application/json';
    }

    public function getResponse(array &$data): string
    {
        return json_encode($data, $this->getJsonEncodeType() ?? 0);
    }

    protected function getJsonEncodeType(): ?int
    {
        return null;
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

abstract class AbstractJSONDataStructureFormatter extends AbstractDataStructureFormatter
{
    public function getContentType()
    {
        return 'application/json';
    }

    protected function printData(&$data)
    {
        echo json_encode($data, $this->getJsonEncodeType() ?? 0);
    }

    protected function getJsonEncodeType(): ?int
    {
        return null;
    }
}

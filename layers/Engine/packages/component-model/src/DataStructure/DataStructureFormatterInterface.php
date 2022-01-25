<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

interface DataStructureFormatterInterface
{
    public function getName(): string;
    public function getFormattedData($data);
    public function getContentType();
    public function outputResponse(array &$data);
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

use PoP\ComponentModel\DataStructureFormatters\DataStructureFormatterInterface;

interface DataStructureManagerInterface
{
    public function addDataStructureFormatter(DataStructureFormatterInterface $formatter): void;
    public function setDefaultDataStructureFormatter(DataStructureFormatterInterface $formatter): void;
    public function getDataStructureFormatter(string $name = null): DataStructureFormatterInterface;
}

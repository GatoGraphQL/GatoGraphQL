<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

interface DataStructureManagerInterface
{
    public function addDataStructureFormatter(DataStructureFormatterInterface $formatter): void;
    public function getDataStructureFormatter(string $name = null): DataStructureFormatterInterface;
}

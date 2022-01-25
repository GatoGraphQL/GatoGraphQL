<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

interface DataStructureFormatterInterface
{
    public function getName(): string;
    public function getFormattedData(array $data): array;
    public function getContentType(): string;
    public function getOutputContent(array &$data): string;
}

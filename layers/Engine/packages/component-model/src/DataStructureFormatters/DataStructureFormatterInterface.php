<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructureFormatters;

interface DataStructureFormatterInterface
{
    public function getName(): string;
    /**
     * @return array<string,mixed>
     */
    public function getFormattedData(array $data): array;
    public function getContentType(): string;
    public function getOutputContent(array &$data): string;
}

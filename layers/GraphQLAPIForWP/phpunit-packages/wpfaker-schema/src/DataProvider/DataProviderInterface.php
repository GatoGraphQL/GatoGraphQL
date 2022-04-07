<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\DataProvider;

use GraphQLAPI\WPFakerSchema\Exception\DatasetFileExtensionException;

interface DataProviderInterface
{
    /**
     * The parsed WordPress data from a pre-defined export file.
     *
     * @return array<string,mixed>
     * @throws DatasetFileExtensionException If the fixed dataset file does not end with ".xml" or ".php"
     */
    public function getFixedDataset(): array;
    /**
     * The file providing the fixed dataset. It can be either:
     *
     * - a PHP file with the array containing the data
     * - an XML WordPress data export file
     *
     * In the 1st case, the data is retrieved directly from the PHP file.
     * In the 2nd case, the file is parsed via `WPDataParser`.
     */
    public function getFixedDatasetFile(): string;
}

<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\DataProvider;

use GraphQLAPI\WPFakerSchema\Exception\DatasetFileExtensionException;
use PoP\Root\Services\BasicServiceTrait;

class DataProvider implements DataProviderInterface
{
    use BasicServiceTrait;

    /** @var array<string,mixed> */
    private array $data;
    private bool $initialized = false;

    /**
     * The parsed WordPress data from a pre-defined export file.
     *
     * @return array<string,mixed>
     * @throws DatasetFileExtensionException If the fixed dataset file does not end with ".xml" or ".php"
     */
    public function getFixedDataset(): array
    {
        if ($this->initialized) {
            $this->initializeFixedDataset();
        }
        return $this->data;
    }

    /**
     * @throws DatasetFileExtensionException If the fixed dataset file does not end with ".xml" or ".php"
     */
    protected function initializeFixedDataset(): void
    {
        $file = $this->getFixedDatasetFile();

        /**
         * Validate the file is right
         */
        if (!(str_ends_with($file, '.xml') || str_ends_with($file, '.php'))) {
            throw new DatasetFileExtensionException(
                sprintf(
                    $this->__(
                        'The fixed dataset must be either a PHP or XML file, but file "%s" was provided',
                        'wpfaker-schema'
                    ),
                    $file
                )
            );
        }
        
        /**
         * WordPress data export XML file
         */
        if (str_ends_with($file, '.xml')) {

        }

        /**
         * WordPress data as PHP file
         */
        
         $this->data = require 'includeme.php'; 
    }

    /**
     * The file providing the fixed dataset. It can be either:
     *
     * - a PHP file with the array containing the data
     * - an XML WordPress data export file
     *
     * In the 1st case, the data is retrieved directly from the PHP file.
     * In the 2nd case, the file is parsed via `WPDataParser`.
     */
    public function getFixedDatasetFile(): string
    {
        return dirname(__DIR__, 2) . '/resources/fixed-dataset.wordpress.php';
    }
}

<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\DataProvider;

use GraphQLAPI\WPFakerSchema\Exception\DatasetFileException;
use PoP\Root\Services\BasicServiceTrait;
use PoPBackbone\WPDataParser\Exception\ParserException;
use PoPBackbone\WPDataParser\WPDataParser;

use function str_ends_with;

class DataProvider implements DataProviderInterface
{
    use BasicServiceTrait;

    /** @var array<string,mixed> */
    private array $data;
    private bool $initialized = false;

    /**
     * The parsed WordPress data from one or more pre-defined export files.
     *
     * Data is provided under the following entries:
     *
     * - "authors"
     * - "posts"
     * - "categories"
     * - "tags"
     * - "terms"
     *
     * Meta-data is provided under the following entries:
     *
     * - "base_url"
     * - "base_blog_url"
     * - "version"
     *
     * @return array<string,mixed>
     * @throws DatasetFileException If the fixed dataset file does not end with ".xml" or ".php"
     */
    public function getFixedDataset(): array
    {
        if (!$this->initialized) {
            $this->initializeFixedDatasetSources();
        }
        return $this->data;
    }

    /**
     * Merge the data from all different sources.
     *
     * @throws DatasetFileException If the fixed dataset file does not end with ".xml" or ".php"
     * @throws ParserException If the fixed dataset file is invalid
     */
    protected function initializeFixedDatasetSources(): void
    {
        $this->initialized = true;
        $this->data = [];
        $files = $this->getFixedDatasetFiles();

        foreach ($files as $file) {
            $isXML = str_ends_with($file, '.xml');
            /**
             * Validate all files are either XML or PHP,
             * or throw an Exception otherwise
             */
            if (!($isXML || str_ends_with($file, '.php'))) {
                throw new DatasetFileException(
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
             * Retrieve the WordPress data from the source file
             */
            $fileData = $isXML ? (new WPDataParser())->parse($file) : require $file;
            if (!is_array($fileData)) {
                throw new DatasetFileException(
                    sprintf(
                        $this->__(
                            'File "%s" does not contain a valid dataset',
                            'wpfaker-schema'
                        ),
                        $file
                    )
                );
            }
            $this->mergeDataset($fileData);
        }
    }

    /**
     * @param array<string,mixed> $data
     */
    protected function mergeDataset(array $data): void
    {
        /**
         * Merge the datasets together.
         *
         * Use `array_merge` instead of `array_merge_recursive`
         * as to have downstream datasets add more data, not
         * replace the one from upstream sources.
         */
        foreach ($data as $entityType => $entityData) {
            /**
             * Merge properties "authors", "posts", "categories",
             * "tags" and "terms"
             */
            if (is_array($entityData)) {
                $this->data[$entityType] = array_merge(
                    $this->data[$entityType] ?? [],
                    $entityData
                );
                continue;
            }
            /**
             * Properties "base_url", "base_blog_url" and "version"
             * need not be overriden.
             */
            if (isset($this->data[$entityType])) {
                continue;
            }
            $this->data[$entityType] = $entityData;
        }
    }

    /**
     * The file providing the fixed dataset. It can be either:
     *
     * - a PHP file with the array containing the data
     * - an XML WordPress data export file
     *
     * In the 1st case, the data is retrieved directly from the PHP file.
     * In the 2nd case, the file is parsed via `WPDataParser`.
     *
     * @return string[]
     */
    public function getFixedDatasetFiles(): array
    {
        return [
            dirname(__DIR__, 2) . '/resources/fixed-dataset.wordpress.php',
        ];
    }
}

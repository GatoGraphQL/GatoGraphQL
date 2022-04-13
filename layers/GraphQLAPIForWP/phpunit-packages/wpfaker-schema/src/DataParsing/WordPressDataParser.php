<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WPFakerSchema\DataParsing;

use PHPUnitForGraphQLAPI\WPFakerSchema\Exception\DatasetFileException;
use PoPBackbone\WPDataParser\WPDataParser;

class WordPressDataParser
{
    /**
     * Read the file, and extract its data.
     *
     * The file can be either:
     *
     * - a PHP file with the array containing the data
     * - an XML WordPress data export file
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
   public function mergeDataFromFile(array $data, string $file): array
    {
        $isXML = str_ends_with($file, '.xml');
        /**
         * Validate all files are either XML or PHP,
         * or throw an Exception otherwise
         */
        if (!($isXML || str_ends_with($file, '.php'))) {
            throw new DatasetFileException(
                sprintf(
                    'The fixed dataset must be either a PHP or XML file, but file "%s" was provided',
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
                    'File "%s" does not contain a valid dataset',
                    $file
                )
            );
        }
        return $this->mergeData($data, $fileData);
    }

    /**
     * Merge the datasets from different files.
     *
     * @param array<string,mixed> $data
     * @param array<string,mixed> $fileData
     * @return array<string,mixed>
     */
    protected function mergeData(array $data, array $fileData): array
    {
        /**
         * Use `array_merge` instead of `array_merge_recursive`
         * as to have downstream datasets add more data, not
         * replace the one from upstream sources.
         */
        foreach ($fileData as $entityType => $entityData) {
            /**
             * Merge properties "authors", "posts", "categories",
             * "tags" and "terms"
             */
            if (is_array($entityData)) {
                $data[$entityType] = array_merge(
                    $data[$entityType] ?? [],
                    $entityData
                );
                continue;
            }
            /**
             * Properties "base_url", "base_blog_url" and "version"
             * need not be overriden.
             */
            if (isset($data[$entityType])) {
                continue;
            }
            $data[$entityType] = $entityData;
        }
        return $data;
    }
}

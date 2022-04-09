<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\State;

use Brain\Faker\Providers;
use Faker\Generator;
use GraphQLAPI\WPFakerSchema\Exception\DatasetFileException;
use PoPBackbone\WPDataParser\WPDataParser;

use function Brain\faker;

class MockDataStore
{
    protected Generator $faker;
    protected Providers $wpFaker;
    /** @var array<string,mixed> */
    protected array $data = [];

    /**
     * @param string[] $files
     */
    function __construct(array $files) {
        $this->faker = faker();
        // @phpstan-ignore-next-line
        $this->wpFaker = $this->faker->wp();
        $this->initializeFakePostDataEntries($files);
        $this->seedFakeData();
    }

    protected function seedFakeData(): void
    {
        foreach (($this->data['posts'] ?? []) as $fakePostDataEntry) {
            $this->wpFaker->post(['id' => $fakePostDataEntry['post_id'], ...$fakePostDataEntry]);
        }
    }

    /**
     * @param string[] $files
     */
    protected function initializeFakePostDataEntries(array $files): void
    {
        foreach ($files as $file) {
            $isXML = str_ends_with($file, '.xml');
            /**
             * Validate all files are either XML or PHP,
             * or throw an Exception otherwise
             */
            if (!($isXML || str_ends_with($file, '.php'))) {
                throw new DatasetFileException(
                    sprintf(
                        // $this->__(
                            'The fixed dataset must be either a PHP or XML file, but file "%s" was provided',
                        //     'wpfaker-schema'
                        // ),
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
                        // $this->__(
                            'File "%s" does not contain a valid dataset',
                        //     'wpfaker-schema'
                        // ),
                        $file
                    )
                );
            }
            $this->mergeDataset($fileData);
        }
    }

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
}

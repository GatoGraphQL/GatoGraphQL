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
    function __construct(
        array $files,
        array $options = []
    ) {
        $this->faker = faker();
        // @phpstan-ignore-next-line
        $this->wpFaker = $this->faker->wp();
        foreach ($files as $file) {
            $this->mergeDataFromFile($file);
        }
        $this->seedFakeData($options);
    }

    /**
     * Read the file, and extract its data.
     *
     * The file can be either:
     *
     * - a PHP file with the array containing the data
     * - an XML WordPress data export file
     */
    protected function mergeDataFromFile(string $file): void
    {
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
        $this->mergeData($fileData);
    }

    /**
     * Merge the datasets from different files.
     *
     * @param array<string,mixed> $data
     */
    protected function mergeData(array $data): void
    {
        /**
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
     * Inject the dataset into BrainFaker
     *
     * @param array<string,mixed> $options
     * @see https://github.com/Brain-WP/BrainFaker#what-is-mocked
     */
    protected function seedFakeData(array $options): void
    {
        $userDataEntries = ($this->data['authors'] ?? []);
        if ($limitUsers = $options['limit-users'] ?? 0) {
            array_splice($userDataEntries, 0, $limitUsers);
        }
        foreach ($userDataEntries as $userDataEntry) {
            $this->wpFaker->user([
                'id' => $userDataEntry['author_id'],
                'login' => $userDataEntry['author_login'],
                'email' => $userDataEntry['author_email'],
                'display_name' => $userDataEntry['author_display_name'],
                'first_name' => $userDataEntry['author_first_name'],
                'last_name' => $userDataEntry['author_last_name'],
            ]);
        }

        // $termSlugIDs = [];
        $categoryDataEntries = ($this->data['categories'] ?? []);
        if ($limitCategories = $options['limit-categories'] ?? 0) {
            array_splice($categoryDataEntries, 0, $limitCategories);
        }
        foreach ($categoryDataEntries as $categoryDataEntry) {
            $this->wpFaker->term([
                'id' => $categoryDataEntry['term_id'],
                'taxonomy' => 'category',
                'term_id' => $categoryDataEntry['term_id'],
                'name' => $categoryDataEntry['cat_name'],
                'slug' => $categoryDataEntry['category_nicename'],
                'parent' => $categoryDataEntry['category_parent'],
                'description' => $categoryDataEntry['category_description'],
            ]);
            // $termSlugIDs['category'][$categoryDataEntry['category_nicename']] = $categoryDataEntry['term_id'];
        }

        $postDataEntries = ($this->data['posts'] ?? []);
        if ($limitPosts = $options['limit-posts'] ?? 0) {
            array_splice($postDataEntries, 0, $limitPosts);
        }
        foreach ($postDataEntries as $postDataEntry) {
            $postID = $postDataEntry['post_id'];
            /**
             * @todo Map relationships between posts and tags/categories
             * Currently not supported because BrainFaker is not mocking `wp_get_post_terms`
             */
            // $postCategoryIDs = [];
            // foreach (($postDataEntry['categories'] ?? []) as $postCategoryDataEntry) {
            //     $postCategoryID = $termSlugIDs[$postCategoryDataEntry['domain']][$postCategoryDataEntry['slug']] ?? null;
            //     if ($postCategoryID === null) {
            //         throw new DatasetFileException(
            //             sprintf(
            //                 // $this->__(
            //                 'There is no category with slug %s',
            //                 //     'wpfaker-schema'
            //                 // ),
            //                 $postCategoryDataEntry['slug']
            //             )
            //         );
            //     }
            //     $postCategoryIDs[] = $postCategoryID;
            // }
            $this->wpFaker->post([
                'id' => $postID,
                ...$postDataEntry
            ]);
            foreach (($postDataEntry['comments'] ?? []) as $postCommentDataEntry) {
                $this->wpFaker->comment([
                    ...$postCommentDataEntry,
                    'id' => $postCommentDataEntry['comment_id'],
                    'comment_post_ID' => $postID,
                    'user_id' => $postCommentDataEntry['comment_user_id'],
                ]);
            }
        }
    }
}

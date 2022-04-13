<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WPFakerSchema\Standalone;

use Brain\Faker\Providers;
use Faker\Generator;
use GraphQLByPoP\GraphQLServer\Standalone\AbstractFixtureQueryExecutionGraphQLServerTestCase;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnitForGraphQLAPI\WPFakerSchema\Exception\DatasetFileException;
use PHPUnitForGraphQLAPI\WPFakerSchema\MockFunctions\WordPressMockFunctionContainer;
use PoPBackbone\WPDataParser\WPDataParser;

use function Brain\faker;
use function Brain\fakerReset;
use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\stubEscapeFunctions;

abstract class AbstractWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractFixtureQueryExecutionGraphQLServerTestCase
{
    use MockeryPHPUnitIntegration;
    
    protected static Generator $faker;
    protected static Providers $wpFaker;

    public static function setUpBeforeClass(): void
    {
        // Execute in this order!
        static::setUpFaker();
        parent::setUpBeforeClass();
    }

    /**
     * Extend "Brain Monkey setup for WordPress" with "Brain Faker" capabilities.
     *
     * @see https://github.com/leoloso/BrainFaker#tests-setup
     */
    protected static function setUpFaker(): void
    {
        self::$faker = faker();
        // @phpstan-ignore-next-line
        self::$wpFaker = self::$faker->wp();

        $data = [];
        foreach (static::getWordPressExportDataFiles() as $file) {
            $data = static::mergeDataFromFile($data, $file);
        }
        
        $options = static::getSeedDataOptions();
        static::seedWordPressDataIntoFaker($data, $options);
        static::mockFunctions();
    }

    public static function tearDownAfterClass(): void
    {
        fakerReset();
        parent::tearDownAfterClass();
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
    protected static function getWordPressExportDataFiles(): array
    {
        return [
            dirname(__DIR__, 2) . '/resources/fixed-dataset.wordpress.php',
        ];
    }

    /**
     * return array<string,mixed>
     */
    protected static function getSeedDataOptions(): array
    {
        return [];
    }

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
    protected static function mergeDataFromFile(array $data, string $file): array
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
        return self::mergeData($data, $fileData);
    }

    /**
     * Merge the datasets from different files.
     *
     * @param array<string,mixed> $data
     * @param array<string,mixed> $fileData
     * @return array<string,mixed>
     */
    private static function mergeData(array $data, array $fileData): array
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

    /**
     * Inject the dataset into BrainFaker
     *
     * @param array<string,mixed> $data
     * @param array<string,mixed> $options
     * @see https://github.com/Brain-WP/BrainFaker#what-is-mocked
     */
    protected static function seedWordPressDataIntoFaker(array $data, array $options): void
    {
        // Seed the entities retrieved from the export file
        $userDataEntries = ($data['authors'] ?? []);
        if ($limitUsers = $options['limit-users'] ?? 0) {
            $userDataEntries = array_slice($userDataEntries, 0, $limitUsers, true);
        }
        foreach ($userDataEntries as $userDataEntry) {
            self::$wpFaker->user([
                'id' => $userDataEntry['author_id'],
                'login' => $userDataEntry['author_login'],
                'email' => $userDataEntry['author_email'],
                'display_name' => $userDataEntry['author_display_name'],
                'first_name' => $userDataEntry['author_first_name'],
                'last_name' => $userDataEntry['author_last_name'],
            ]);
        }

        $taxonomies = ['post_tag', 'category'];
        $termSlugCounter = [];
        $postDataEntries = ($data['posts'] ?? []);
        if ($limitPosts = $options['limit-posts'] ?? 0) {
            $postDataEntries = array_slice($postDataEntries, 0, $limitPosts, true);
        }
        foreach ($postDataEntries as $postDataEntry) {
            $postID = $postDataEntry['post_id'];
            self::$wpFaker->post([
                'id' => $postID,
                ...$postDataEntry
            ]);
            foreach (($postDataEntry['comments'] ?? []) as $postCommentDataEntry) {
                self::$wpFaker->comment([
                    ...$postCommentDataEntry,
                    'id' => $postCommentDataEntry['comment_id'],
                    'comment_post_ID' => $postID,
                    'user_id' => $postCommentDataEntry['comment_user_id'],
                ]);
            }
            // Count tags/categories
            foreach ($taxonomies as $taxonomy) {
                $postTaxonomyTermDataEntries = array_filter(
                    $postDataEntry['terms'] ?? [],
                    fn (array $postTermDataEntry) => $postTermDataEntry['domain'] === $taxonomy
                );
                foreach ($postTaxonomyTermDataEntries as $postCategoryDataEntry) {
                    $termSlugCounter[$taxonomy][$postCategoryDataEntry['slug']] = ($termSlugCounter[$taxonomy][$postCategoryDataEntry['slug']] ?? 0) + 1;
                }
            }
            /**
             * @todo Map relationships between posts and tags/categories
             * Currently not supported because BrainFaker is not mocking `wp_get_post_terms`
             */
            // ...
        }

        $categoryDataEntries = ($data['categories'] ?? []);
        if ($limitCategories = $options['limit-categories'] ?? 0) {
            $categoryDataEntries = array_slice($categoryDataEntries, 0, $limitCategories, true);
        }
        foreach ($categoryDataEntries as $categoryDataEntry) {
            self::$wpFaker->term([
                'id' => $categoryDataEntry['term_id'],
                'taxonomy' => 'category',
                'term_id' => $categoryDataEntry['term_id'],
                'name' => $categoryDataEntry['cat_name'],
                'slug' => $categoryDataEntry['category_nicename'],
                'parent' => $categoryDataEntry['category_parent'],
                'description' => $categoryDataEntry['category_description'],
                'count' => $termSlugCounter['category'][$categoryDataEntry['category_nicename']] ?? 0,
            ]);
        }

        $tagDataEntries = ($data['tags'] ?? []);
        if ($limitTags = $options['limit-tags'] ?? 0) {
            $tagDataEntries = array_slice($tagDataEntries, 0, $limitTags, true);
        }
        foreach ($tagDataEntries as $tagDataEntry) {
            self::$wpFaker->term([
                'id' => $tagDataEntry['term_id'],
                'taxonomy' => 'post_tag',
                'term_id' => $tagDataEntry['term_id'],
                'name' => $tagDataEntry['tag_name'],
                'slug' => $tagDataEntry['tag_slug'],
                'description' => $tagDataEntry['tag_description'],
                'count' => $termSlugCounter['post_tag'][$tagDataEntry['tag_slug']] ?? 0,
            ]);
        }
    }

    /**
     * Mock needed WordPress functions
     */
    protected static function mockFunctions(): void
    {
        // Stub `esc_sql`
        stubEscapeFunctions();

        expect('get_option')
            ->with('date_format', Mockery::any())
            ->andReturn('Y-m-d');

        $wpMockFunctionContainer = new WordPressMockFunctionContainer();

        expect('mysql2date')
            ->andReturnUsing($wpMockFunctionContainer->mySQL2Date(...));
    }

    /**
     * @return string[]
     */
    protected static function getGraphQLServerComponentClasses(): array
    {
        return [
            ...parent::getGraphQLServerComponentClasses(),
            ...[
                \PHPUnitForGraphQLAPI\WPFakerSchema\Component::class,
            ]
        ];
    }
}

<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WPFakerSchema\Seed;

use Brain\Faker\Providers;

class FakerWordPressDataSeeder
{
    /**
     * Inject the dataset into BrainFaker
     *
     * @param array<string,mixed> $data
     * @param array<string,mixed> $options
     * @see https://github.com/Brain-WP/BrainFaker#what-is-mocked
     */
    public function seedWordPressDataIntoFaker(
        Providers $wpFaker,
        array $data,
        array $options
    ): void {
        // Seed the entities retrieved from the export file
        $userDataEntries = ($data['authors'] ?? []);
        if ($limitUsers = $options['limit-users'] ?? 0) {
            $userDataEntries = array_slice($userDataEntries, 0, $limitUsers, true);
        }
        foreach ($userDataEntries as $userDataEntry) {
            $wpFaker->user([
                'id' => $userDataEntry['author_id'],
                'login' => $userDataEntry['author_login'],
                'email' => $userDataEntry['author_email'],
                'display_name' => $userDataEntry['author_display_name'],
                'first_name' => $userDataEntry['author_first_name'],
                'last_name' => $userDataEntry['author_last_name'],
                // Treat the admin user as "administrator", everyone else as "subscriber"
                'role' => $userDataEntry['author_login'] === 'admin' ? 'administrator' : 'subscriber',
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
            $wpFaker->post([
                'id' => $postID,
                ...$postDataEntry
            ]);
            foreach (($postDataEntry['comments'] ?? []) as $postCommentDataEntry) {
                $wpFaker->comment([
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
            $wpFaker->term([
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
            $wpFaker->term([
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
}

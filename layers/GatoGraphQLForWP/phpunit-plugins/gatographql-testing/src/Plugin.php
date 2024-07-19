<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\UserMetaKeys;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Executers\BulkPluginActivationDeactivationExecuter;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Executers\GatoGraphQLAdminEndpointsTestExecuter;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Hooks\AddDummyCustomAdminEndpointHook;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Endpoints\AdminRESTAPIEndpointManager;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Settings\Options;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Utilities\CustomHeaderAppender;
use WP_REST_Response;

use function add_action;
use function delete_option;
use function flush_rewrite_rules;
use function get_option;

class Plugin
{
    public function initialize(): void
    {
        /**
         * Send custom headers needed for development
         */
        new CustomHeaderAppender();

        /**
         * Initialize REST endpoints
         */
        new AdminRESTAPIEndpointManager();

        /**
         * Initialize all the hooks
         */
        new AddDummyCustomAdminEndpointHook();

        /**
         * Test executers
         */
        new GatoGraphQLAdminEndpointsTestExecuter();
        new BulkPluginActivationDeactivationExecuter();

        /**
         * Executing `flush_rewrite_rules` at the end of the execution
         * of the REST controller doesn't work, so do it at the
         * beginning instead, if set via a flag.
         */
        if (get_option(Options::FLUSH_REWRITE_RULES)) {
            delete_option(Options::FLUSH_REWRITE_RULES);
            add_action('init', flush_rewrite_rules(...), PHP_INT_MAX);
        }

        $this->maybeAdaptRESTAPIResponse();

        add_action('init', $this->registerTestingTaxonomies(...));
        add_action('init', $this->registerRESTFields(...));
    }

    /**
     * For the internal tests only, remove entries from
     * the REST response.
     */
    protected function maybeAdaptRESTAPIResponse(): void
    {
        /**
         * Make sure the origin of the request is some test.
         *
         * Watch out: the classes containing these constants are not
         * included in this plugin, hence the values are hardcoded.
         * Update with caution!
         *
         * @see layers/GatoGraphQLForWP/phpunit-packages/webserver-requests/src/Constants/CustomHeaders.php
         * @see layers/GatoGraphQLForWP/phpunit-packages/webserver-requests/src/Constants/CustomHeaderValues.php
         *
         * @todo Create a new package to make it DRY
         */
        $headerName = 'X-Request-Origin'; // CustomHeaders::REQUEST_ORIGIN;
        $headerValue = 'WebserverRequestTest'; // CustomHeaderValues::REQUEST_ORIGIN_VALUE
        /**
         * The custom header somehow arrives prepended with "HTTP_",
         * replacing all "-" with "_", and in uppercase
         */
        $header = strtoupper('HTTP_' . str_replace('-', '_', $headerName));
        // phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
        if (($_SERVER[$header] ?? null) !== $headerValue) {
            return;
        }

        /**
         * Remove the "_link" entry from the WP REST API response,
         * so that the GraphQL response does not include the domain,
         * and then the same tests work for both "Integration Tests"
         * and "PROD Integration Tests".
         *
         * The hooks for CPTs must be generated one by one.
         *
         * @see https://stackoverflow.com/a/53505460
         * @see wp-includes/rest-api/endpoints/class-wp-rest-posts-controller.php
         */
        $cpts = ['post', 'page', 'attachment'];
        $hooks = [
            'rest_prepare_application_password',
            'rest_prepare_attachment',
            'rest_prepare_autosave',
            'rest_prepare_block_type',
            'rest_prepare_comment',
            'rest_prepare_nav_menu_item',
            'rest_prepare_menu_location',
            'rest_prepare_block_pattern',
            'rest_prepare_plugin',
            'rest_prepare_status',
            'rest_prepare_post_type',
            'rest_prepare_revision',
            'rest_prepare_sidebar',
            'rest_prepare_taxonomy',
            'rest_prepare_theme',
            'rest_prepare_url_details',
            'rest_prepare_user',
            'rest_prepare_widget_type',
            'rest_prepare_widget',
            ...array_map(
                fn (string $cpt) => 'rest_prepare_' . $cpt,
                $cpts
            )
        ];
        foreach ($hooks as $hook) {
            \add_filter(
                $hook,
                $this->removeRESTAPIResponseLink(...),
                PHP_INT_MAX
            );
        }
    }

    public function removeRESTAPIResponseLink(WP_REST_Response $data): WP_REST_Response
    {
        foreach ($data->get_links() as $_linkKey => $_linkVal) {
            $data->remove_link($_linkKey);
        }
        return $data;
    }

    /**
     * Taxonomies used for testing the plugin
     */
    protected function registerTestingTaxonomies(): void
    {
        \register_taxonomy(
            'dummy-tag',
            [],
            $this->getTaxonomyArgs(
                false,
                __('Dummy Tag'),
                __('Dummy Tags'),
                __('dummy tag'),
                __('dummy tags'),
            )
        );

        \register_taxonomy(
            'dummy-tag-two',
            [],
            $this->getTaxonomyArgs(
                false,
                __('Dummy Tag (Two)'),
                __('Dummy Tags (Two)'),
                __('dummy tag (two)'),
                __('dummy tags (two)'),
            )
        );

        \register_taxonomy(
            'dummy-category',
            [],
            $this->getTaxonomyArgs(
                true,
                __('Dummy Category'),
                __('Dummy Categories'),
                __('dummy category'),
                __('dummy categories'),
            )
        );

        \register_taxonomy(
            'dummy-category-two',
            [],
            $this->getTaxonomyArgs(
                true,
                __('Dummy Category (Two)'),
                __('Dummy Categories (Two)'),
                __('dummy category (two)'),
                __('dummy categories (two)'),
            )
        );

        \register_post_type(
            'dummy-cpt',
            $this->getCustomPostTypeArgs(
                [
                    'dummy-tag',
                    'dummy-category',
                ],
                __('Dummy CPT'),
                __('Dummy CPTs'),
                __('dummy CPTs'),
            )
        );

        \register_post_type(
            'dummy-cpt-two',
            $this->getCustomPostTypeArgs(
                [
                    'dummy-tag-two',
                    'dummy-category-two',
                ],
                __('Dummy CPT (Two)'),
                __('Dummy CPTs (Two)'),
                __('dummy CPTs (two)'),
            )
        );
    }

    /**
     * Taxonomies used for testing the plugin
     */
    protected function registerRESTFields(): void
    {
        \register_rest_field(
            'user',
            UserMetaKeys::APP_PASSWORD,
            [
                'get_callback' => $this->userMetaCallback(...),
            ]
        );
    }

    /**
     * @param array<string,mixed> $userData
     */
    public function userMetaCallback(array $userData, string $field_name): mixed
    {
        return \get_user_meta($userData['id'], $field_name, true);
    }

    /**
     * Labels for registering the taxonomy
     *
     * @param string $name_uc Singular name uppercase
     * @param string $names_uc Plural name uppercase
     * @param string $name_lc Singulare name lowercase
     * @param string $names_lc Plural name lowercase
     * @return array<string,mixed>
     */
    protected function getTaxonomyArgs(
        bool $hierarchical,
        string $name_uc,
        string $names_uc,
        string $name_lc,
        string $names_lc,
    ): array {
        return array(
            'label' => $names_uc,
            'labels' => $this->getTaxonomyLabels(
                $name_uc,
                $names_uc,
                $name_lc,
                $names_lc,
            ),
            'hierarchical' => $hierarchical,
            'public' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => false,
            'show_in_rest' => true,
        );
    }

    /**
     * Labels for registering the taxonomy
     *
     * @param string $name_uc Singular name uppercase
     * @param string $names_uc Plural name uppercase
     * @param string $name_lc Singulare name lowercase
     * @param string $names_lc Plural name lowercase
     * @return array<string,string>
     */
    protected function getTaxonomyLabels(
        string $name_uc,
        string $names_uc,
        string $name_lc,
        string $names_lc,
    ): array {
        return array(
            'name'                           => $names_uc,
            'singular_name'                  => $name_uc,
            'menu_name'                      => $names_uc,
            'search_items'                   => \sprintf(\__('Search %s', 'gatographql'), $names_uc),
            'all_items'                      => $names_uc,//\sprintf(\__('All %s', 'gatographql'), $names_uc),
            'edit_item'                      => \sprintf(\__('Edit %s', 'gatographql'), $name_uc),
            'update_item'                    => \sprintf(\__('Update %s', 'gatographql'), $name_uc),
            'add_new_item'                   => \sprintf(\__('Add New %s', 'gatographql'), $name_uc),
            'new_item_name'                  => \sprintf(\__('Add New %s', 'gatographql'), $name_uc),
            'view_item'                      => \sprintf(\__('View %s', 'gatographql'), $name_uc),
            'popular_items'                  => \sprintf(\__('Popular %s', 'gatographql'), $names_lc),
            'separate_items_with_commas'     => \sprintf(\__('Separate %s with commas', 'gatographql'), $names_lc),
            'add_or_remove_items'            => \sprintf(\__('Add or remove %s', 'gatographql'), $name_lc),
            'choose_from_most_used'          => \sprintf(\__('Choose from the most used %s', 'gatographql'), $names_lc),
            'not_found'                      => \sprintf(\__('No %s found', 'gatographql'), $names_lc),
        );
    }

    /**
     * Arguments for registering the post type
     *
     * @param string[] $taxonomies
     * @return array<string,mixed>
     */
    protected function getCustomPostTypeArgs(
        array $taxonomies,
        string $name_uc,
        string $names_uc,
        string $names_lc,
    ): array {
        return array(
            'public' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'publicly_queryable' => true,
            'label' => $name_uc,
            'labels' => $this->getCustomPostTypeLabels($name_uc, $names_uc, $names_lc),
            'capability_type' => 'post',
            'hierarchical' => true,
            'exclude_from_search' => false,
            'show_in_admin_bar' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'supports' => [
                'title',
                'editor',
                'excerpt',
                'author',
                'revisions',
                'thumbnail',
                'comments',
                'custom-fields',
            ],
            // 'rewrite' => ['slug' => $slugBase],
            'taxonomies' => $taxonomies,
        );
    }

    /**
     * Labels for registering the post type
     *
     * @param string $name_uc Singular name uppercase
     * @param string $names_uc Plural name uppercase
     * @param string $names_lc Plural name lowercase
     * @return array<string,string>
     */
    protected function getCustomPostTypeLabels(
        string $name_uc,
        string $names_uc,
        string $names_lc,
    ): array {
        return array(
            'name'               => $names_uc,
            'singular_name'      => $name_uc,
            'add_new'            => sprintf(\__('Add New %s', 'gatographql'), $name_uc),
            'add_new_item'       => sprintf(\__('Add New %s', 'gatographql'), $name_uc),
            'edit_item'          => sprintf(\__('Edit %s', 'gatographql'), $name_uc),
            'new_item'           => sprintf(\__('New %s', 'gatographql'), $name_uc),
            'all_items'          => $names_uc,//sprintf(\__('All %s', 'gatographql'), $names_uc),
            'view_item'          => sprintf(\__('View %s', 'gatographql'), $name_uc),
            'search_items'       => sprintf(\__('Search %s', 'gatographql'), $names_uc),
            'not_found'          => sprintf(\__('No %s found', 'gatographql'), $names_lc),
            'not_found_in_trash' => sprintf(\__('No %s found in Trash', 'gatographql'), $names_lc),
            'parent_item_colon'  => sprintf(\__('Parent %s:', 'gatographql'), $name_uc),
        );
    }
}

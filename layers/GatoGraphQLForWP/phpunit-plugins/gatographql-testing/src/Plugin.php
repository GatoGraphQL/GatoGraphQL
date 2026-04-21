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
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Webserver\LandoAdapter;
use WP_REST_Response;

use function add_action;
use function delete_option;
use function flush_rewrite_rules;
use function get_option;
use function register_nav_menus;

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
         * Adapt the Lando webserver
         */
        new LandoAdapter();

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
        add_action('init', $this->registerTestingPHPOnlyBlocks(...));
        add_action('after_setup_theme', $this->registerMenuLocations(...));
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
            'additional-post-tag',
            [
                'post',
            ],
            $this->getTaxonomyArgs(
                false,
                __('Additional Post Tag'),
                __('Additional Post Tags'),
                __('additional post tag'),
                __('additional post tags'),
            )
        );

        \register_taxonomy(
            'additional-post-category',
            [
                'post',
            ],
            $this->getTaxonomyArgs(
                true,
                __('Additional Post Category'),
                __('Additional Post Categories'),
                __('additional post category'),
                __('additional post categories'),
            )
        );

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
            'additional-dummy-tag',
            [],
            $this->getTaxonomyArgs(
                false,
                __('Additional dummy Tag'),
                __('Additional dummy Tags'),
                __('additional dummy tag'),
                __('additional dummy tags'),
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
            'dummy-tag-three',
            [],
            $this->getTaxonomyArgs(
                false,
                __('Dummy Tag (Three)'),
                __('Dummy Tags (Three)'),
                __('dummy tag (three)'),
                __('dummy tags (three)'),
            )
        );

        \register_taxonomy(
            'dummy-tag-four',
            [],
            $this->getTaxonomyArgs(
                false,
                __('Dummy Tag (Four)'),
                __('Dummy Tags (Four)'),
                __('dummy tag (four)'),
                __('dummy tags (four)'),
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
            'additional-dummy-category',
            [],
            $this->getTaxonomyArgs(
                true,
                __('Additional dummy Category'),
                __('Additional dummy Categories'),
                __('additional dummy category'),
                __('additional dummy categories'),
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

        \register_taxonomy(
            'dummy-category-three',
            [],
            $this->getTaxonomyArgs(
                true,
                __('Dummy Category (Three)'),
                __('Dummy Categories (Three)'),
                __('dummy category (three)'),
                __('dummy categories (three)'),
            )
        );

        \register_taxonomy(
            'dummy-category-four',
            [],
            $this->getTaxonomyArgs(
                true,
                __('Dummy Category (Four)'),
                __('Dummy Categories (Four)'),
                __('dummy category (four)'),
                __('dummy categories (four)'),
            )
        );

        \register_post_type(
            'dummy-cpt',
            $this->getCustomPostTypeArgs(
                [
                    'dummy-tag',
                    'dummy-category',
                    'additional-dummy-tag',
                    'additional-dummy-category',
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

        \register_post_type(
            'dummy-cpt-three',
            $this->getCustomPostTypeArgs(
                [
                    'dummy-tag-three',
                    'dummy-category-three',
                ],
                __('Dummy CPT (Three)'),
                __('Dummy CPTs (Three)'),
                __('dummy CPTs (three)'),
            )
        );

        \register_post_type(
            'dummy-cpt-four',
            $this->getCustomPostTypeArgs(
                [
                    'dummy-tag-four',
                    'dummy-category-four',
                ],
                __('Dummy CPT (Four)'),
                __('Dummy CPTs (Four)'),
                __('dummy CPTs (four)'),
            )
        );
    }

    /**
     * Taxonomies used for testing the plugin
     */
    protected function registerRESTFields(): void
    {
        $metaKeys = [
            UserMetaKeys::APP_PASSWORD,
            UserMetaKeys::APP_PASSWORD_ADMIN,
            UserMetaKeys::APP_PASSWORD_EDITOR,
            UserMetaKeys::APP_PASSWORD_AUTHOR,
            UserMetaKeys::APP_PASSWORD_CONTRIBUTOR,
            UserMetaKeys::APP_PASSWORD_SUBSCRIBER,
        ];
        foreach ($metaKeys as $metaKey) {
            \register_rest_field(
                'user',
                $metaKey,
                [
                    'get_callback' => $this->userMetaCallback(...),
                ]
            );
        }
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
                'page-attributes', // Allow to set the parent post
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

    /**
     * Register theme menu locations
     */
    protected function registerMenuLocations(): void
    {
        register_nav_menus([
            'primary' => __('Header', 'bricks-child-playground'),
            'secondary' => __('Footer', 'bricks-child-playground'),
        ]);
    }

    /**
     * Register PHP-only Gutenberg blocks for testing.
     *
     * Requires WordPress 7.0+ (or the Gutenberg plugin >= 21.9), which
     * introduced the `supports.autoRegister` flag for fully PHP-registered blocks.
     *
     * @see https://make.wordpress.org/core/2026/03/03/php-only-block-registration/
     * @see https://getbutterfly.com/php-only-block-registration-in-wordpress/
     */
    protected function registerTestingPHPOnlyBlocks(): void
    {
        $this->registerAlertBlock('gatographql-testing/alert');
        $this->registerAlertBlock('gatographql-testing/duplicate-alert');
        $this->registerMarqueeBlock();
        $this->registerAuthorBoxBlock();
    }

    /**
     * Example 1 — Alert / Notice Block
     */
    protected function registerAlertBlock(string $blockName): void
    {
        \register_block_type(
            $blockName,
            [
                'title' => 'Alert',
                'description' => 'A notice or alert message.',
                'category' => 'text',
                'icon' => 'warning',
                'attributes' => [
                    'header' => [
                        'type' => 'string',
                        'default' => 'Notice',
                        'label' => 'Header',
                    ],
                    'message' => [
                        'type' => 'string',
                        'default' => 'This is an important notice.',
                        'label' => 'Message',
                    ],
                    'implications' => [
                        'type' => 'string',
                        'default' => 'Review the details and take action if needed.',
                        'label' => 'Implications',
                    ],
                    'severity' => [
                        'type' => 'string',
                        'enum' => ['Info', 'Warning', 'Error', 'Success'],
                        'default' => 'Info',
                        'label' => 'Severity',
                    ],
                    'dismissible' => [
                        'type' => 'boolean',
                        'default' => false,
                        'label' => 'Dismissible',
                    ],
                ],
                'supports' => [
                    'autoRegister' => true,
                    'color' => ['text' => true, 'background' => true],
                    'spacing' => ['padding' => true],
                    'border' => [
                        'color' => true,
                        'radius' => true,
                        'style' => true,
                        'width' => true,
                    ],
                ],
                'render_callback' => $this->renderAlertBlock(...),
            ]
        );
    }

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderAlertBlock(array $attributes): string
    {
        $severity = strtolower((string) ($attributes['severity'] ?? 'info'));
        $wrapper = \get_block_wrapper_attributes([
            'class' => 'gatographql-alert gatographql-alert--' . $severity,
            'role' => 'alert',
            'aria-label' => (string) ($attributes['severity'] ?? 'Info'),
        ]);

        $dismiss = !empty($attributes['dismissible'])
            ? '<button class="gatographql-alert__dismiss" aria-label="Dismiss">&times;</button>'
            : '';

        $header = (string) ($attributes['header'] ?? '');
        $headerHTML = $header !== ''
            ? sprintf('<h4 class="gatographql-alert__header">%s</h4>', \esc_html($header))
            : '';
        $implications = (string) ($attributes['implications'] ?? '');
        $implicationsHTML = $implications !== ''
            ? sprintf('<p class="gatographql-alert__implications">%s</p>', \esc_html($implications))
            : '';

        return sprintf(
            '<div %s>%s%s<p class="gatographql-alert__message">%s</p>%s</div>',
            $wrapper,
            $dismiss,
            $headerHTML,
            \esc_html((string) ($attributes['message'] ?? '')),
            $implicationsHTML
        );
    }

    /**
     * Example 2 — Scrolling Marquee Block
     */
    protected function registerMarqueeBlock(): void
    {
        \register_block_type(
            'gatographql-testing/marquee',
            [
                'title' => 'Marquee',
                'description' => 'A single scrolling text row.',
                'category' => 'text',
                'icon' => 'leftright',
                'attributes' => [
                    'text' => [
                        'type' => 'string',
                        'default' => 'Your scrolling text here —',
                        'label' => 'Text',
                    ],
                    'speed' => [
                        'type' => 'string',
                        'enum' => ['Slow', 'Medium', 'Fast'],
                        'default' => 'Medium',
                        'label' => 'Speed',
                    ],
                    'direction' => [
                        'type' => 'string',
                        'enum' => ['Left', 'Right'],
                        'default' => 'Left',
                        'label' => 'Direction',
                    ],
                ],
                'supports' => [
                    'autoRegister' => true,
                    'align' => ['wide', 'full'],
                    'color' => ['text' => true, 'background' => true],
                    'spacing' => ['padding' => true],
                    'typography' => [
                        'fontSize' => true,
                        'fontWeight' => true,
                        'fontStyle' => true,
                    ],
                ],
                'render_callback' => $this->renderMarqueeBlock(...),
            ]
        );
    }

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderMarqueeBlock(array $attributes): string
    {
        $speedMap = ['Slow' => 0.5, 'Medium' => 1.0, 'Fast' => 2.0];
        $step = $speedMap[$attributes['speed'] ?? 'Medium'] ?? 1.0;
        $direction = (($attributes['direction'] ?? 'Left') === 'Right') ? 'right' : 'left';

        $wrapper = \get_block_wrapper_attributes(['class' => 'gatographql-marquee']);

        return sprintf(
            '<div %s><div class="gatographql-marquee__row" data-step="%s" data-repeat="16" data-direction="%s">%s</div></div>',
            $wrapper,
            \esc_attr((string) $step),
            \esc_attr($direction),
            \esc_html((string) ($attributes['text'] ?? ''))
        );
    }

    /**
     * Example 3 — Author Box Block
     *
     * Demonstrates a dynamically-built `enum` populated from `get_users()`
     * at registration time.
     */
    protected function registerAuthorBoxBlock(): void
    {
        $users = \get_users(['number' => -1, 'fields' => ['user_login']]);
        $userEnum = array_values(array_map(
            static fn ($u): string => (string) $u->user_login,
            $users
        ));

        // Avoid registering with an empty enum (would break the SelectControl)
        if ($userEnum === []) {
            return;
        }

        \register_block_type(
            'gatographql-testing/author-box',
            [
                'title' => 'Author Box',
                'description' => 'A server-rendered author card.',
                'category' => 'text',
                'icon' => 'admin-users',
                'attributes' => [
                    'userLogin' => [
                        'type' => 'string',
                        'enum' => $userEnum,
                        'default' => $userEnum[0],
                        'label' => 'User',
                    ],
                    'showAvatar' => [
                        'type' => 'boolean',
                        'default' => true,
                        'label' => 'Show Avatar',
                    ],
                    'showBio' => [
                        'type' => 'boolean',
                        'default' => true,
                        'label' => 'Show Bio',
                    ],
                    'showEmail' => [
                        'type' => 'boolean',
                        'default' => true,
                        'label' => 'Show Email',
                    ],
                ],
                'supports' => [
                    'autoRegister' => true,
                    'align' => ['wide', 'full'],
                    'color' => ['text' => true, 'background' => true],
                    'spacing' => ['padding' => true],
                    'border' => ['radius' => true],
                ],
                'render_callback' => $this->renderAuthorBoxBlock(...),
            ]
        );
    }

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderAuthorBoxBlock(array $attributes): string
    {
        $user = \get_user_by('login', (string) ($attributes['userLogin'] ?? ''));
        if (!$user) {
            return '';
        }

        $wrapper = \get_block_wrapper_attributes(['class' => 'gatographql-author-box']);

        $avatar = !empty($attributes['showAvatar'])
            ? \get_avatar($user->ID, 80, '', '', ['class' => 'gatographql-author-box__avatar'])
            : '';
        $bio = !empty($attributes['showBio']) && $user->description !== ''
            ? '<p class="gatographql-author-box__bio">' . \esc_html($user->description) . '</p>'
            : '';
        $email = !empty($attributes['showEmail'])
            ? '<a href="mailto:' . \esc_attr($user->user_email) . '">' . \esc_html($user->user_email) . '</a>'
            : '';

        return sprintf(
            '<div %s>%s<div class="gatographql-author-box__body"><h3 class="gatographql-author-box__name">%s</h3>%s<div class="gatographql-author-box__links">%s</div></div></div>',
            $wrapper,
            $avatar,
            \esc_html($user->display_name),
            $bio,
            $email
        );
    }
}

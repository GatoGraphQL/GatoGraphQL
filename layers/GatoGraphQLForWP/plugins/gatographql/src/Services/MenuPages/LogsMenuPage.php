<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use PoPSchema\Logger\Constants\LoggerSeverity;
use GatoGraphQL\GatoGraphQL\Log\Controllers\FileHandler\{ File, FileController, FileListTable, SearchListTable };
use GatoGraphQL\GatoGraphQL\Log\Controllers\PageController;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\AbstractPluginMenuPage;
use PoP\ComponentModel\App;
use PoPSchema\Logger\Module as LoggerModule;
use PoPSchema\Logger\ModuleConfiguration as LoggerModuleConfiguration;
use WP_List_Table;

use function get_plugin_page_hook;

/**
 * @phpcs:ignoreFile
 *
 * This file is based on WooCommerce.
 *
 * @see https://github.com/woocommerce/woocommerce/blob/9.8.5/plugins/woocommerce/includes/admin/views/html-admin-page-status.php#L37
 */
class LogsMenuPage extends AbstractPluginMenuPage implements PageController
{
    /**
     * Instance of FileController.
     */
    private FileController $file_controller;

    /**
     * Instance of FileListTable or SearchListTable.
     */
    private FileListTable|SearchListTable|null $list_table = null;

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?UserAuthorizationInterface $userAuthorization = null;

    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        if ($this->userAuthorization === null) {
            /** @var UserAuthorizationInterface */
            $userAuthorization = $this->instanceManager->getInstance(UserAuthorizationInterface::class);
            $this->userAuthorization = $userAuthorization;
        }
        return $this->userAuthorization;
    }

    public function isServiceEnabled(): bool
    {
        /** @var LoggerModuleConfiguration */
        $loggerModuleConfiguration = App::getModule(LoggerModule::class)->getConfiguration();
        if (!$loggerModuleConfiguration->enableLogs()) {
            return false;
        }

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->displayEnableLogsSettingsOption()) {
            return false;
        }

        return parent::isServiceEnabled();
    }

    /**
     * Initialize the class instance
     */
    public function initialize(): void
    {
        parent::initialize();

        add_action('admin_init', function () {

            $pageHook = get_plugin_page_hook($this->getScreenID(), 'admin.php');
            \add_action(
                "load-{$pageHook}",
                function (): void {
                    if (!$this->isCurrentScreen()) {
                        return;
                    }

                    $this->file_controller = new FileController();

                    $params = $this->get_query_params(array( 'view' ));

                    $this->setup_screen_options($params['view']);
                    $this->handle_list_table_bulk_actions($params['view']);
                }
            );
        });
    }

    public function getMenuPageSlug(): string
    {
        return 'logs';
    }

    public function getMenuPageTitle(): string
    {
        return __('Logs', 'gatographql');
    }

    /**
     * Copied from WooCommerce
     *
     * @see https://github.com/woocommerce/woocommerce/blob/9.8.5/plugins/woocommerce/includes/admin/views/html-admin-page-status.php#L37
     */
    public function print(): void
    {
        ?>
        <div class="wrap woocommerce">
            <h1 class="screen-reader-text"><?php _e('Logs', 'gatographql'); ?></h1>
            <?php $this->render_section_nav(); ?>
            <?php $this->render(); ?>
        </div>
        <?php
    }

    /**
     * Render navigation to switch between logs browsing and settings.
     */
    private function render_section_nav(): void
    {
        $params       = $this->get_query_params(array( 'view' ));
        if ($params['view'] === 'list_files') {
            return;
        }

        $browse_url   = $this->get_logs_tab_url();
        ?>
        <ul class="subsubsub">
            <li>
                <?php
                printf(
                    '<a href="%1$s">%2$s</a>',
                    esc_url($browse_url),
                    esc_html__('← Browse log files', 'gatographql')
                );
                ?>
            </li>
        </ul>
        <br class="clear">
        <?php
    }

    /**
     * Get the canonical URL for the Logs tab of the Status admin page.
     *
     * @return string
     */
    public function get_logs_tab_url(): string
    {
        return add_query_arg(
            array(
                'page' => $this->getScreenID(),
            ),
            admin_url('admin.php')
        );
    }

    /**
     * Code below copied from WooCommerce
     *
     * @see wp-content/plugins/woocommerce/src/Internal/Admin/Logging/PageController.php method `render_filev2`
     */
    public function render(): void
    {
        $params = $this->get_query_params(array( 'view' ));
        switch ($params['view']) {
            case 'search_results':
                $this->render_search_results_view();
                break;
            case 'single_file':
                $this->render_single_file_view();
                break;
            case 'list_files':
            default:
                $this->render_list_files_view();
                break;
        }
    }

    /**
     * Render the file list view.
     *
     * @return void
     */
    private function render_list_files_view(): void
    {
        $params     = $this->get_query_params(array( 'order', 'orderby', 'source', 'view' ));
        $defaults   = $this->get_query_param_defaults();
        $list_table = $this->get_list_table($params['view']);

        $list_table->prepare_items();

        ?>
        <header id="logs-header" class="gatogql-logs-header">
            <h2>
                <?php esc_html_e('Browse log files', 'gatographql'); ?>
            </h2>
            <?php $this->render_search_field(); ?>
        </header>
        <form id="logs-list-table-form" method="get">
            <input type="hidden" name="page" value="<?php echo $this->getScreenID(); ?>" />
            <?php foreach ($params as $key => $value) : ?>
                <?php if ($value !== $defaults[ $key ]) : ?>
                    <input
                        type="hidden"
                        name="<?php echo esc_attr($key); ?>"
                        value="<?php echo esc_attr($value); ?>"
                    />
                <?php endif; ?>
            <?php endforeach; ?>
            <?php $list_table->display(); ?>
        </form>
        <?php
    }

    /**
     * Render the single file view.
     *
     * @return void
     */
    private function render_single_file_view(): void
    {
        $params = $this->get_query_params(array( 'file_id', 'view' ));
        $file   = $this->file_controller->get_file_by_id($params['file_id']);

        if (is_wp_error($file)) {
            ?>
            <div class="notice notice-error notice-inline">
                <?php echo wp_kses_post(wpautop($file->get_error_message())); ?>
                <?php
                printf(
                    '<p><a href="%1$s">%2$s</a></p>',
                    esc_url($this->get_logs_tab_url()),
                    esc_html__('Return to the file list.', 'gatographql')
                );
                ?>
            </div>
            <?php

            return;
        }

        $rotations         = $this->file_controller->get_file_rotations($file->get_file_id());
        $rotation_url_base = add_query_arg('view', 'single_file', $this->get_logs_tab_url());

        $download_url           = add_query_arg(
            array(
                'action'  => 'export',
                'file_id' => array( $file->get_file_id() ),
            ),
            wp_nonce_url($this->get_logs_tab_url(), 'bulk-log-files')
        );
        $delete_url             = add_query_arg(
            array(
                'action'  => 'delete',
                'file_id' => array( $file->get_file_id() ),
            ),
            wp_nonce_url($this->get_logs_tab_url(), 'bulk-log-files')
        );
        $delete_confirmation_js = sprintf(
            "return window.confirm( '%s' )",
            esc_js(__('Delete this log file permanently?', 'gatographql'))
        );

        $stream      = $file->get_stream();
        $line_number = 1;

        ?>
        <header id="logs-header" class="gatogql-logs-header">
            <h2>
                <?php
                printf(
                    // translators: %s is the name of a log file.
                    esc_html__('Viewing log file %s', 'gatographql'),
                    sprintf(
                        '<span class="file-id">%s</span>',
                        esc_html($file->get_file_id())
                    )
                );
                ?>
            </h2>
            <?php if (count($rotations) > 1) : // @phpstan-ignore-line
                ?>
                <nav class="gatogql-logs-single-file-rotations">
                    <h3><?php esc_html_e('File rotations:', 'gatographql'); ?></h3>
                    <ul class="gatogql-logs-rotation-links">
                        <?php if (isset($rotations['current'])) : ?>
                            <?php
                            printf(
                                '<li><a href="%1$s" class="button button-small button-%2$s">%3$s</a></li>',
                                esc_url(add_query_arg('file_id', $rotations['current']->get_file_id(), $rotation_url_base)),
                                $file->get_file_id() === $rotations['current']->get_file_id() ? 'primary' : 'secondary',
                                esc_html__('Current', 'gatographql')
                            );
                            unset($rotations['current']);
                            ?>
                        <?php endif; ?>
                        <?php foreach ($rotations as $rotation) : // @phpstan-ignore-line
                            printf(
                                '<li><a href="%1$s" class="button button-small button-%2$s">%3$s</a></li>',
                                esc_url(add_query_arg('file_id', $rotation->get_file_id(), $rotation_url_base)),
                                $file->get_file_id() === $rotation->get_file_id() ? 'primary' : 'secondary',
                                absint($rotation->get_rotation())
                            );
                            ?>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            <?php endif; ?>
            <div class="gatogql-logs-single-file-actions">
                <?php
                // Download button.
                printf(
                    '<a href="%1$s" class="button button-secondary">%2$s</a>',
                    esc_url($download_url),
                    esc_html__('Download', 'gatographql')
                );
                ?>
                <?php
                // Delete button.
                printf(
                    '<a href="%1$s" class="button button-secondary" onclick="%2$s">%3$s</a>',
                    esc_url($delete_url),
                    esc_attr($delete_confirmation_js),
                    esc_html__('Delete permanently', 'gatographql')
                );
                ?>
            </div>
        </header>
        <section id="logs-entries" class="gatogql-logs-entries">
            <?php while (! feof($stream)) : // @phpstan-ignore-line
                $line = fgets($stream); // @phpstan-ignore-line
                if (is_string($line)) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- format_line does the escaping.
                    echo $this->format_line($line, $line_number);
                    ++$line_number;
                }
                ?>
            <?php endwhile; ?>
        </section>
        <script>
            // Clear the line number hash and highlight with a click.
            document.documentElement.addEventListener( 'click', ( event ) => {
                if ( window.location.hash && ! event.target.classList.contains( 'line-anchor' ) ) {
                    let scrollPos = document.documentElement.scrollTop;
                    window.location.hash = '';
                    document.documentElement.scrollTop = scrollPos;
                    history.replaceState( null, '', window.location.pathname + window.location.search );
                }
            } );
        </script>
        <?php
    }

    /**
     * Render the search results view.
     *
     * @return void
     */
    private function render_search_results_view(): void
    {
        $params     = $this->get_query_params(array( 'view' ));
        $list_table = $this->get_list_table($params['view']);

        $list_table->prepare_items();

        ?>
        <header id="logs-header" class="gatogql-logs-header">
            <h2><?php esc_html_e('Search results', 'gatographql'); ?></h2>
            <?php $this->render_search_field(); ?>
        </header>
        <?php $list_table->display(); ?>
        <?php
    }

    /**
     * Get the default values for URL query params for FileHandler views.
     *
     * @return string[]
     */
    public function get_query_param_defaults(): array
    {
        return array(
            'file_id' => '',
            'order'   => $this->file_controller::DEFAULTS_GET_FILES['order'],
            'orderby' => $this->file_controller::DEFAULTS_GET_FILES['orderby'],
            'search'  => '',
            'source'  => $this->file_controller::DEFAULTS_GET_FILES['source'],
            'view'    => 'list_files',
        );
    }

    /**
     * Get and validate URL query params for FileHandler views.
     *
     * @param array $param_keys Optional. The names of the params you want to get.
     *
     * @return array
     */
    public function get_query_params(array $param_keys = array()): array // @phpstan-ignore-line
    {
        $defaults = $this->get_query_param_defaults();
        $params   = filter_input_array(
            INPUT_GET,
            array(
                'file_id' => array(
                    'filter'  => FILTER_CALLBACK,
                    'options' => function ($file_id) {
                        return sanitize_file_name(wp_unslash($file_id));
                    },
                ),
                'order'   => array(
                    'filter'  => FILTER_VALIDATE_REGEXP,
                    'options' => array(
                        'regexp'  => '/^(asc|desc)$/i',
                        'default' => $defaults['order'],
                    ),
                ),
                'orderby' => array(
                    'filter'  => FILTER_VALIDATE_REGEXP,
                    'options' => array(
                        'regexp'  => '/^(created|modified|source|size)$/',
                        'default' => $defaults['orderby'],
                    ),
                ),
                'search'  => array(
                    'filter'  => FILTER_CALLBACK,
                    'options' => function ($search) {
                        return esc_html(wp_unslash($search));
                    },
                ),
                'source'  => array(
                    'filter'  => FILTER_CALLBACK,
                    'options' => function ($source) {
                        return File::sanitize_source(wp_unslash($source));
                    },
                ),
                'view'    => array(
                    'filter'  => FILTER_VALIDATE_REGEXP,
                    'options' => array(
                        'regexp'  => '/^(list_files|single_file|search_results|settings)$/',
                        'default' => $defaults['view'],
                    ),
                ),
            ),
            false
        );
        $params   = wp_parse_args($params, $defaults);

        if (count($param_keys) > 0) {
            $params = array_intersect_key($params, array_flip($param_keys));
        }

        return $params;
    }

    /**
     * Get and cache an instance of the list table.
     *
     * @param string $view The current view, which determines which list table class to get.
     *
     * @return FileListTable|SearchListTable
     */
    private function get_list_table(string $view)
    {
        if ($this->list_table !== null) {
            return $this->list_table;
        }

        switch ($view) {
            case 'list_files':
                $this->list_table = new FileListTable($this->file_controller, $this);
                break;
            case 'search_results':
                $this->list_table = new SearchListTable($this->file_controller, $this);
                break;
        }

        return $this->list_table; // @phpstan-ignore-line
    }

    /**
     * Register screen options for the logging views.
     *
     * @param string $view The current view within the Logs tab.
     *
     * @return void
     *
     * @internal For exclusive usage of WooCommerce core, backwards compatibility not guaranteed.
     */
    public function setup_screen_options(string $view): void
    {
        $list_table = null;

        if (in_array($view, array( 'list_files', 'search_results' ), true)) {
            $list_table = $this->get_list_table($view);
        }

        if ($list_table instanceof WP_List_Table) {
            // Ensure list table columns are initialized early enough to enable column hiding, if available.
            $list_table->prepare_column_headers();

            add_screen_option(
                'per_page',
                array(
                    'default' => $list_table->get_per_page_default(),
                    'option'  => $list_table->getPerPageUserOptionKey(),
                )
            );
        }
    }

    /**
     * Process bulk actions initiated from the log file list table.
     *
     * @param string $view The current view within the Logs tab.
     *
     * @return void
     *
     * @internal For exclusive usage of WooCommerce core, backwards compatibility not guaranteed.
     */
    public function handle_list_table_bulk_actions(string $view): void
    {
        $params = $this->get_query_params(array( 'file_id' ));

        // Bail if this is not the list table view.
        if ('list_files' !== $view) {
            return;
        }

        $action = $this->get_list_table($view)->current_action();

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : $this->get_logs_tab_url();

        if ($action) {
            check_admin_referer('bulk-log-files');

            $schemaEditorAccessCapability = $this->getUserAuthorization()->getSchemaEditorAccessCapability();

            if (! current_user_can($schemaEditorAccessCapability)) {
                wp_die(esc_html__('You do not have permission to manage log files.', 'gatographql'));
            }

            $sendback = remove_query_arg(array( 'deleted' ), wp_get_referer());

            // Multiple file_id[] params will be filtered separately, but assigned to $files as an array.
            $file_ids = $params['file_id'];

            if (! is_array($file_ids) || count($file_ids) < 1) {
                wp_safe_redirect($sendback);
                exit;
            }

            switch ($action) {
                case 'export':
                    if (1 === count($file_ids)) {
                        $export_error = $this->file_controller->export_single_file(reset($file_ids));
                    } else {
                        $export_error = $this->file_controller->export_multiple_files($file_ids);
                    }

                    if (is_wp_error($export_error)) {
                        wp_die(wp_kses_post($export_error->get_error_message()));
                    }
                    break;
                case 'delete':
                    $deleted  = $this->file_controller->delete_files($file_ids);
                    $sendback = add_query_arg('deleted', $deleted, $sendback);

                    /**
                     * If the delete action was triggered on the single file view, don't redirect back there
                     * since the file doesn't exist anymore.
                     */
                    $sendback = remove_query_arg(array( 'view', 'file_id' ), $sendback);
                    break;
            }

            $sendback = remove_query_arg(array( 'action', 'action2' ), $sendback);

            wp_safe_redirect($sendback);
            exit;
        } elseif (! empty($_REQUEST['_wp_http_referer'])) {
            $removable_args = array( '_wp_http_referer', '_wpnonce', 'action', 'action2', 'filter_action' );
            wp_safe_redirect(remove_query_arg($removable_args, $request_uri));
            exit;
        }

        $deleted = filter_input(INPUT_GET, 'deleted', FILTER_VALIDATE_INT);

        if (is_numeric($deleted)) {
            add_action(
                'admin_notices',
                function () use ($deleted) {
                    ?>
                    <div class="notice notice-info is-dismissible">
                        <p>
                            <?php
                            printf(
                            // translators: %s is a number of files.
                                esc_html(_n('%s log file deleted.', '%s log files deleted.', $deleted, 'gatographql')),
                                esc_html(number_format_i18n($deleted))
                            );
                            ?>
                        </p>
                    </div>
                    <?php
                }
            );
        }
    }

    /**
     * Format a log file line.
     *
     * @param string $line        The unformatted log file line.
     * @param int    $line_number The line number.
     *
     * @return string
     */
    private function format_line(string $line, int $line_number): string
    {
        $classes = array( 'line' );

        $line = esc_html($line);
        if (empty($line)) {
            $line = '&nbsp;';
        }

        $segments      = explode(' ', $line, 3);
        $has_timestamp = false;
        $has_level     = false;

        if (isset($segments[0]) && false !== strtotime($segments[0])) {
            $classes[]     = 'log-entry';
            $segments[0]   = sprintf(
                '<span class="log-timestamp">%s</span>',
                $segments[0]
            );
            $has_timestamp = true;
        }

        if (isset($segments[1]) && in_array($segments[1], LoggerSeverity::ALL)) {
            $segments[1] = sprintf(
                '<span class="%1$s">%2$s</span>',
                esc_attr('log-level log-level--' . strtolower($segments[1])),
                esc_html(ucwords(strtolower($segments[1])))
            );
            $has_level   = true;
        }

        if (isset($segments[2]) && $has_timestamp && $has_level) {
            $message_chunks = explode('CONTEXT:', $segments[2], 2);
            if (isset($message_chunks[1])) {
                try {
                    $maybe_json = html_entity_decode(addslashes(trim($message_chunks[1])));

                    // Decode for validation.
                    $context = json_decode($maybe_json, false, 512, JSON_THROW_ON_ERROR);

                    // Re-encode to make it pretty.
                    $context = wp_json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                    $message_chunks[1] = sprintf(
                        '<details><summary>%1$s</summary>%2$s</details>',
                        esc_html__('Additional context', 'gatographql'),
                        stripslashes($context) // @phpstan-ignore-line
                    );

                    $segments[2] = implode(' ', $message_chunks);
                    $classes[]   = 'has-context';
                } catch (\JsonException $exception) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
                    // It's not valid JSON so don't do anything with it.
                }
            }
        }

        if (count($segments) > 1) {
            $line = implode(' ', $segments);
        }

        $classes = implode(' ', $classes);

        return sprintf(
            '<span id="L%1$d" class="%2$s">%3$s%4$s</span>',
            absint($line_number),
            esc_attr($classes),
            sprintf(
                '<a href="#L%1$d" class="line-anchor"></a>',
                absint($line_number)
            ),
            sprintf(
                '<span class="line-content">%s</span>',
                wp_kses_post($line)
            )
        );
    }

    /**
     * Render a form for searching within log files.
     *
     * @return void
     */
    private function render_search_field(): void
    {
        $params     = $this->get_query_params(array( 'date_end', 'date_filter', 'date_start', 'search', 'source' ));
        $defaults   = $this->get_query_param_defaults();
        $file_count = $this->file_controller->get_files($params, true);

        if ($file_count > 0) {
            ?>
            <form id="logs-search" class="gatogql-logs-search" method="get">
                <fieldset class="gatogql-logs-search-fieldset">
                    <input type="hidden" name="page" value="<?php echo $this->getScreenID(); ?>" />
                    <input type="hidden" name="view" value="search_results" />
                    <?php foreach ($params as $key => $value) : ?>
                        <?php if ($value !== $defaults[ $key ]) : ?>
                            <input
                                type="hidden"
                                name="<?php echo esc_attr($key); ?>"
                                value="<?php echo esc_attr($value); ?>"
                            />
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <label for="logs-search-field">
                        <?php esc_html_e('Search within these files', 'gatographql'); ?>
                        <input
                            id="logs-search-field"
                            class="gatogql-logs-search-field"
                            type="text"
                            name="search"
                            value="<?php echo esc_attr($params['search']); ?>"
                        />
                    </label>
                    <?php submit_button(__('Search', 'gatographql'), 'secondary', null, false); ?><?php // @phpstan-ignore-line ?>
                </fieldset>
                <?php if ($file_count >= $this->file_controller::SEARCH_MAX_FILES) : ?>
                    <div class="gatogql-logs-search-notice">
                        <?php
                        printf(
                            // translators: %s is a number.
                            esc_html__(
                                '⚠️ Only %s files can be searched at one time. Try filtering the file list before searching.',
                                'gatographql'
                            ),
                            esc_html(number_format_i18n($this->file_controller::SEARCH_MAX_FILES))
                        );
                        ?>
                    </div>
                <?php endif; ?>
            </form>
            <?php
        }
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        // CSS
        \wp_enqueue_style(
            'gatographql-logs',
            $mainPluginURL . 'assets/css/logs.css',
            array(),
            $mainPluginVersion
        );
    }
}

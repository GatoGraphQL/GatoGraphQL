<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\PluginApp;
use WP_Plugin_Install_List_Table;
use stdClass;

use function get_plugin_data;

// The file containing class WP_Plugin_Install_List_Table is not
// loaded by default in WordPress.
require_once ABSPATH . 'wp-admin/includes/class-wp-plugin-install-list-table.php';

/**
 * Extension Table
 */
class ExtensionListTable extends WP_Plugin_Install_List_Table implements ItemListTableInterface
{
    use ItemListTableTrait;

    /**
     * @return void
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function prepare_items()
    {
        add_filter('install_plugins_tabs', $this->overrideInstallPluginTabs(...));
        add_filter('install_plugins_nonmenu_tabs', $this->overrideInstallPluginNonMenuTabs(...));
        add_filter('plugins_api', $this->overridePluginsAPI(...));
        add_filter('plugins_api_result', $this->overridePluginsAPIResult(...));
        add_filter('plugin_install_action_links', $this->overridePluginInstallActionLinks(...), 10, 2);
        parent::prepare_items();
        remove_filter('plugin_install_action_links', $this->overridePluginInstallActionLinks(...), 10);
        remove_filter('plugins_api_result', $this->overridePluginsAPIResult(...));
        remove_filter('plugins_api', $this->overridePluginsAPI(...));
        remove_filter('install_plugins_nonmenu_tabs', $this->overrideInstallPluginNonMenuTabs(...));
        remove_filter('install_plugins_tabs', $this->overrideInstallPluginTabs(...));

        $this->injectDefaultValuesToItems();
    }

    /**
     * Inject default items (and those that can be retrieved
     * via PHP) via code, so that it's not needed to repeat
     * these extensions.json
     */
    protected function injectDefaultValuesToItems(): void
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginVersion = $mainPlugin->getPluginVersion();
        $pluginURL = $mainPlugin->getPluginURL();
        $gatoGraphQLLogoFile = $pluginURL . 'assets-pro/img/GatoGraphQL-logo.svg';

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();

        /**
         * Retrieve the plugin data for the Gato GraphQL plugin.
         * As all extensions live in the same monorepo, they have
         * the same requirements.
         *
         * @see https://developer.wordpress.org/reference/functions/get_plugin_data/
         */
        $gatoGraphQLPluginData = get_plugin_data($mainPlugin->getPluginFile());

        /** @var array<array<string,mixed>> */
        $items = &$this->items;
        foreach ($items as &$plugin) {
            $plugin['version'] ??= $mainPluginVersion;
            $plugin['author'] ??= sprintf(
                '<a href="%s">%s</a>',
                $gatoGraphQLPluginData['AuthorURI'],
                $gatoGraphQLPluginData['Author']
            );
            $plugin['requires'] ??= $gatoGraphQLPluginData['RequiresWP'];
            $plugin['requires_php'] ??= $gatoGraphQLPluginData['RequiresPHP'];
            $plugin['homepage'] ??= sprintf(
                '%s/extensions/%s',
                $moduleConfiguration->getGatoGraphQLWebsiteURL(),
                $plugin['gato_extension_slug']
            );
            $plugin['icons'] ??= [
                'svg' =>  $gatoGraphQLLogoFile,
                '1x' =>  $gatoGraphQLLogoFile,
            ];
        }
    }

    /**
     * Keep only the "Featured" tab
     *
     * @param string[] $tabs
     * @return string[]
     */
    public function overrideInstallPluginTabs(array $tabs): array
    {
        return array_intersect_key(
            $tabs,
            ['featured' => true]
        );
    }

    /**
     * Remove all tabs
     *
     * @param string[] $tabs
     * @return string[]
     */
    public function overrideInstallPluginNonMenuTabs(array $tabs): array
    {
        return [];
    }

    /**
     * Do not connect to wordpress.org to retrieve data
     */
    public function overridePluginsAPI(): mixed
    {
        return new stdClass();
    }

    /**
     * Hardcoded data with extensions.
     *
     * To see what data must be retrieved, execute:
     *
     * http://api.wordpress.org/plugins/info/1.2/?action=query_plugins&per_page=1
     *
     * @see http://api.wordpress.org/plugins/info/1.2/?action=query_plugins&per_page=1
     */
    public function overridePluginsAPIResult(): mixed
    {
        $extensionsDataSourceFile = PluginApp::getMainPlugin()->getPluginDir() . '/data-sources/extensions.json';
        $extensionsDataSource = file_get_contents($extensionsDataSourceFile);
        if ($extensionsDataSource === false) {
            return new stdClass();
        }
        $extensionsDataSource = $this->replaceVariablesInJSONDataSource($extensionsDataSource);
        $extensionsData = json_decode($extensionsDataSource, true);
        return (object) $extensionsData;
    }

    /**
     * The JSON data contains variable placeholders that
     * must be replaced to their actual values
     */
    protected function replaceVariablesInJSONDataSource(string $extensionsDataSource): string
    {
        return str_replace(
            '{$PLUGIN_URL}',
            rtrim(PluginApp::getMainPlugin()->getPluginURL(), '/'),
            $extensionsDataSource
        );
    }

    /**
     * Replace "Install Now" with "Get Extension"
     *
     * @param string[] $action_links
     * @param array<string,mixed> $plugin
     * @return string[]
     */
    public function overridePluginInstallActionLinks(array $action_links, array $plugin): array
    {
        if (str_starts_with($action_links[0] ?? '', '<a class="install-now button"')) {
            $action_links[0] = sprintf(
                '<a class="install-now button" data-slug="%s" href="%s" aria-label="%s" data-name="%s" target="%s">%s%s</a>',
                esc_attr($plugin['slug']),
                esc_url($plugin['homepage']),
                /* translators: %s: Plugin name and version. */
                esc_attr(sprintf(_x('Get extension %s', 'plugin'), $plugin['name'])),
                esc_attr($plugin['name']),
                '_blank',
                __('Get Extension', 'gato-graphql'),
                HTMLCodes::OPEN_IN_NEW_WINDOW
            );
        }
        return $action_links;
    }

    /**
     * @return void
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function no_items()
    {
        if (isset($this->error)) {
            parent::no_items();
        } else { ?>
            <div class="no-plugin-results"><?php _e('Ooops something went wrong: No extensions found. Please contact the admin.', 'gato-graphql'); ?></div>
            <?php
        }
    }

    /**
     * Adapt the generated HTML content
     * @return void
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function display_rows()
    {
        ob_start();
        parent::display_rows();
        $html = ob_get_clean();

        if ($html === false) {
            return;
        }

        $html = $this->adaptDisplayRowsHTML($html);

        echo $html;
    }

    /**
     * Adapt the generated HTML content
     */
    protected function adaptDisplayRowsHTML(string $html): string
    {
        foreach ((array) $this->items as $plugin) {
            /**
             * Change the "More information" link to open the
             * extension website, and not the plugin page
             * on wp.org (which does not exist!)
             */
            // Code copied from `display_rows` in the parent class
            $details_link = self_admin_url(
                'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] .
                '&amp;TB_iframe=true&amp;width=600&amp;height=550'
            );
            // Replace it with this other link
            $extensionModule = 'GatoGraphQL\\GatoGraphQL\\extensions\\' . $plugin['gato_extension_slug'];
            $adaptedDetailsLink = \admin_url(sprintf(
                'admin.php?page=%s&%s=%s&%s=%s&TB_iframe=true&width=600&height=550',
                App::request('page') ?? App::query('page', ''),
                RequestParams::TAB,
                RequestParams::TAB_DOCS,
                RequestParams::MODULE,
                urlencode($extensionModule)
            ));
            $html = str_replace(
                esc_url($details_link),
                esc_url($adaptedDetailsLink),
                $html
            );
        }

        return $html;
    }
}

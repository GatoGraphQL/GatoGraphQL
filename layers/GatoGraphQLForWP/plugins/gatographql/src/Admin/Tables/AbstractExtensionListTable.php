<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
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
 * Abstract Extensions Table class, which lets the implementing class
 * decide how to retrieve the data for the extensions
 */
abstract class AbstractExtensionListTable extends WP_Plugin_Install_List_Table implements ItemListTableInterface
{
    use ItemListTableTrait;

    /**
     * Keep a copy of the $actionLinks for each plugin,
     * so that their corresponding HTML can be modified
     * based on that state.
     *
     * @var array<string,string[]>
     */
    protected array $pluginActionLinks = [];

    /**
     * @return void
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function prepare_items()
    {
        \add_filter('install_plugins_tabs', $this->overrideInstallPluginTabs(...));
        \add_filter('install_plugins_nonmenu_tabs', $this->overrideInstallPluginNonMenuTabs(...));
        \add_filter('plugins_api', $this->overridePluginsAPI(...));
        \add_filter('plugins_api_result', $this->overridePluginsAPIResult(...));
        \add_filter('plugin_install_action_links', $this->overridePluginInstallActionLinks(...), PHP_INT_MAX, 2);
        parent::prepare_items();
        \remove_filter('plugin_install_action_links', $this->overridePluginInstallActionLinks(...), PHP_INT_MAX);
        \remove_filter('plugins_api_result', $this->overridePluginsAPIResult(...));
        \remove_filter('plugins_api', $this->overridePluginsAPI(...));
        \remove_filter('install_plugins_nonmenu_tabs', $this->overrideInstallPluginNonMenuTabs(...));
        \remove_filter('install_plugins_tabs', $this->overrideInstallPluginTabs(...));
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
     * To see what data must be retrieved, execute a query
     * against the wordpress.org plugins API:
     *
     * @see http://api.wordpress.org/plugins/info/1.2/?action=query_plugins&per_page=1
     */
    abstract public function overridePluginsAPIResult(): mixed;

    /**
     * @param array<array<string,mixed>> $items
     * @return array<array<string,mixed>>
     */
    protected function combineExtensionItemsWithCommonPluginData(array $items): array
    {
        $commonPluginData = $this->getCommonPluginData();
        return array_map(
            fn (array $item) => array_merge(
                $commonPluginData,
                $item
            ),
            $items
        );
    }

    /**
     * Common plugin data for the Gato GraphQL plugin and extensions.
     * As all of these are stored in the same monorepo, they have
     * the same author/version/requirements/etc.
     *
     * @return array<string,mixed>
     */
    protected function getCommonPluginData(): array
    {
        $mainPlugin = PluginApp::getMainPlugin();

        /**
         * Watch out! Cannot assign the main plugin version because
         * it produces a bug:
         *
         * If Gato GraphQL v1.5 is installed, and a bundle v1.4
         * is installed, then page Extensions will not show the
         * bundle as having the "Active" button, and also all
         * included extensions as being "Active (via Bundle)".
         *
         * This happens because `install_plugin_install_status`
         * keeps `$status` as `"install"`, and it must be
         * `"newer_installed"` to add the expected button
         * to Install/Activate the extension.
         */
        // $mainPluginVersion = $mainPlugin->getPluginVersion();
        // $extensionPluginVersion = $mainPluginVersion;
        $extensionPluginVersion = '';

        /**
         * @see https://developer.wordpress.org/reference/functions/get_plugin_data/
         */
        $gatoGraphQLPluginData = get_plugin_data($mainPlugin->getPluginFile());

        return [
            'version' => $extensionPluginVersion,
            'author' => sprintf(
                '<a href="%s">%s</a>',
                $gatoGraphQLPluginData['AuthorURI'],
                $gatoGraphQLPluginData['Author']
            ),
            'requires' => $gatoGraphQLPluginData['RequiresWP'],
            'requires_php' => $gatoGraphQLPluginData['RequiresPHP'],
        ];
    }

    protected function getGatoGraphQLLogoURL(): string
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $pluginURL = $mainPlugin->getPluginURL();
        return $pluginURL . 'assets/img/logos/GatoGraphQL-logo-face.png';
    }

    /**
     * @param string[] $actionLinks
     * @param array<string,mixed> $plugin
     * @return string[]
     */
    public function overridePluginInstallActionLinks(array $actionLinks, array $plugin): array
    {
        /**
         * Keep a copy of the $actionLinks for each plugin,
         * so that their corresponding HTML can be modified
         * based on that state.
         *
         * @var string
         */
        $pluginName = $plugin['name'];
        $this->pluginActionLinks[$pluginName] = $actionLinks;
        /**
         * Replace the "Install Now" action message
         */
        if (
            str_starts_with($actionLinks[0] ?? '', '<a class="install-now button"')
            // Starting from WordPress 6.5
            || str_starts_with($actionLinks[0] ?? '', '<button type="button" class="install-now button button-disabled" disabled="disabled"')
        ) {
            $actionLinks[0] = sprintf(
                '<a class="install-now button" data-slug="%s" href="%s" aria-label="%s" data-name="%s" target="%s">%s</a>',
                esc_attr($plugin['slug']),
                esc_url($plugin['homepage']),
                /* translators: %s: Plugin name and version. */
                esc_attr(sprintf(_x('Get extension %s', 'plugin'), $plugin['name'])),
                esc_attr($plugin['name']),
                '_blank',
                $this->getPluginInstallActionLabel($plugin)
            );
        }
        return $actionLinks;
    }

    /**
     * @param array<string,mixed> $plugin
     */
    public function getPluginInstallActionLabel(array $plugin): string
    {
        return sprintf(
            '%s%s',
            \__('Get Extension', 'gatographql'),
            HTMLCodes::OPEN_IN_NEW_WINDOW
        );
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
            <div class="no-plugin-results"><?php \esc_html_e('Ooops something went wrong: No extensions found. Please contact the admin.', 'gatographql'); ?></div>
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

        $html_safe = $this->adaptDisplayRowsHTML($html);
        if ($this->addArtificialRequestAnExtensionPluginItem()) {
            $html_safe .= $this->getArtificialRequestAnExtensionPluginItem();
        }

        echo $html_safe;
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
            // Replace it with this other link.
            $adaptedDetailsLink = $this->getAdaptedDetailsLink($plugin);
            $html = str_replace(
                esc_url($details_link),
                esc_url($adaptedDetailsLink),
                $html
            );

            /**
             * @var string
             */
            $pluginName = $plugin['name'];
            $pluginCardClassname = 'plugin-card-' . sanitize_html_class($plugin['slug']);
            $pos = strpos($html, $pluginCardClassname);
            if ($pos === false) {
                continue;
            }

            /**
             * Additional classes
             */
            $additionalPluginCardClassnames = $this->getAdditionalPluginCardClassnames($plugin);
            if ($additionalPluginCardClassnames !== null) {
                $html = substr_replace($html, $pluginCardClassname . ' ' . $additionalPluginCardClassnames, $pos, strlen($pluginCardClassname));
            }

            /**
             * Highlight non-installed extensions.
             *
             * Only replace the 1st occurrence, to avoid "access-control" also
             * being replaced in "access-control-visitor-ip"
             */
            $actionLinks = $this->pluginActionLinks[$pluginName] ?? [];
            if (
                str_starts_with($actionLinks[0] ?? '', '<a class="install-now button"')
                // Starting from WordPress 6.5
                || str_starts_with($actionLinks[0] ?? '', '<button type="button" class="install-now button button-disabled" disabled="disabled"')
            ) {
                $html = substr_replace($html, $pluginCardClassname . ' plugin-card-non-installed', $pos, strlen($pluginCardClassname));
            }
        }

        return $html;
    }

    /**
     * @param array<string,mixed> $plugin
     */
    protected function getAdditionalPluginCardClassnames(array $plugin): ?string
    {
        return null;
    }

    protected function addArtificialRequestAnExtensionPluginItem(): bool
    {
        return true;
    }

    /**
     * Get the HTML for the additional "Request an Extension" plugin item
     */
    protected function getArtificialRequestAnExtensionPluginItem(): string
    {
        // Add an additional item
        $additionalItemHTMLPlaceholder = '
            <div class="plugin-card plugin-card-artificially-added">
                <div class="plugin-card-top plugin-card-top-request-extension">
                    <div class="name column-name">
                        <h3>
                            %1$s
                            <img src="%2$s" class="plugin-icon" alt="">
                        </h3>
                    </div>
                    <div class="action-links">
                        <ul class="plugin-action-buttons">
                            <li>
                                <a class="install-now button" href="%3$s" aria-label="%1$s" target="_blank">
                                    %4$s%7$s
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="desc column-description">
                        <p>%5$s</p>
                    </div>
                </div>
                <div class="plugin-card-bottom">
                    <div class="column-compatibility">
                        %6$s
                    </div>
                </div>
            </div>
        ';

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();

        $alternativeGatoGraphQLLogoURL = str_replace(
            'GatoGraphQL-logo-face.png',
            'GatoGraphQL-logo-sleeping.png',
            $this->getGatoGraphQLLogoURL(),
        );

        return sprintf(
            $additionalItemHTMLPlaceholder,
            \__('Missing an Extension?', 'gatographql'),
            $alternativeGatoGraphQLLogoURL,
            $moduleConfiguration->getGatoGraphQLRequestExtensionPageURL(),
            \__('Request an Extension', 'gatographql'),
            \__('Needing an integration with a 3rd-party plugin? Let us know, and we can work on an extension.', 'gatographql'),
            \__('Contact <strong>Gato GraphQL</strong>'),
            HTMLCodes::OPEN_IN_NEW_WINDOW
        );
    }

    /**
     * @param array<string,mixed> $plugin
     */
    abstract protected function getAdaptedDetailsLink(array $plugin): string;
}

<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\PluginApp;

/**
 * Voyager page
 */
class GraphQLVoyagerMenuPage extends AbstractPluginMenuPage
{
    use EnqueueReactMenuPageTrait;

    public function print(): void
    {
        ?>
        <div id="voyager" class="voyager-client">
            <p>
                <?php esc_html_e('Loading...', 'gatographql') ?>
            </p>
        </div>
        <?php
    }

    public function getMenuPageSlug(): string
    {
        return 'voyager';
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
            'gatographql-voyager-client',
            $mainPluginURL . 'assets/css/voyager-client.css',
            array(),
            $mainPluginVersion
        );
        \wp_enqueue_style(
            'gatographql-voyager',
            $mainPluginURL . 'vendor/graphql-by-pop/graphql-clients-for-wp/clients/voyager/assets/vendors/voyager.css',
            array(),
            $mainPluginVersion
        );

        // JS: execute them all in the footer
        $this->enqueueReactAssets(true);
        \wp_enqueue_script(
            'gatographql-voyager',
            $mainPluginURL . 'vendor/graphql-by-pop/graphql-clients-for-wp/clients/voyager/assets/vendors/voyager.standalone.js',
            array('gatographql-react-dom'),
            $mainPluginVersion,
            true
        );
        \wp_enqueue_script(
            'gatographql-voyager-client',
            $mainPluginURL . 'assets/js/voyager-client.js',
            array('gatographql-voyager'),
            $mainPluginVersion,
            true
        );

        // Load data into the script
        \wp_localize_script(
            'gatographql-voyager-client',
            'graphQLByPoPGraphiQLSettings',
            array(
                'nonce' => \wp_create_nonce('wp_rest'),
                'endpoint' => $this->getEndpointHelpers()->getAdminGraphQLEndpoint(),
            )
        );
    }
}

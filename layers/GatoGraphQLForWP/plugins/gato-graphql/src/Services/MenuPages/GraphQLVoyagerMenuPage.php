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
        <div id="voyager" class="voyager-client"><?php echo __('Loading...', 'gato-graphql') ?></div>
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

        $mainPluginURL = PluginApp::getMainPlugin()->getPluginURL();
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();

        // CSS
        \wp_enqueue_style(
            'gato-graphql-voyager-client',
            $mainPluginURL . 'assets/css/voyager-client.css',
            array(),
            $mainPluginVersion
        );
        \wp_enqueue_style(
            'gato-graphql-voyager',
            $mainPluginURL . 'assets/css/vendors/voyager.css',
            array(),
            $mainPluginVersion
        );

        // JS: execute them all in the footer
        $this->enqueueReactAssets(true);
        \wp_enqueue_script(
            'gato-graphql-voyager',
            $mainPluginURL . 'assets/js/vendors/voyager.min.js',
            array('gato-graphql-react-dom'),
            $mainPluginVersion,
            true
        );
        \wp_enqueue_script(
            'gato-graphql-voyager-client',
            $mainPluginURL . 'assets/js/voyager-client.js',
            array('gato-graphql-voyager'),
            $mainPluginVersion,
            true
        );

        // Load data into the script
        \wp_localize_script(
            'gato-graphql-voyager-client',
            'graphQLByPoPGraphiQLSettings',
            array(
                'nonce' => \wp_create_nonce('wp_rest'),
                'endpoint' => $this->getEndpointHelpers()->getAdminGraphQLEndpoint(),
            )
        );
    }
}

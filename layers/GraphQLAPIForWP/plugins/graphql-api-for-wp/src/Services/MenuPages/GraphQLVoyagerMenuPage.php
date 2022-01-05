<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\App;

/**
 * Voyager page
 */
class GraphQLVoyagerMenuPage extends AbstractPluginMenuPage
{
    use EnqueueReactMenuPageTrait;

    public function print(): void
    {
        ?>
        <div id="voyager" class="voyager-client"><?php echo __('Loading...', 'graphql-api') ?></div>
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

        $mainPluginURL = App::getMainPlugin()->getPluginURL();
        $mainPluginVersion = App::getMainPlugin()->getPluginVersion();

        // CSS
        \wp_enqueue_style(
            'graphql-api-voyager-client',
            $mainPluginURL . 'assets/css/voyager-client.css',
            array(),
            $mainPluginVersion
        );
        \wp_enqueue_style(
            'graphql-api-voyager',
            $mainPluginURL . 'assets/css/vendors/voyager.css',
            array(),
            $mainPluginVersion
        );

        // JS: execute them all in the footer
        $this->enqueueReactAssets(true);
        \wp_enqueue_script(
            'graphql-api-voyager',
            $mainPluginURL . 'assets/js/vendors/voyager.min.js',
            array('graphql-api-react-dom'),
            $mainPluginVersion,
            true
        );
        \wp_enqueue_script(
            'graphql-api-voyager-client',
            $mainPluginURL . 'assets/js/voyager-client.js',
            array('graphql-api-voyager'),
            $mainPluginVersion,
            true
        );

        // Load data into the script
        \wp_localize_script(
            'graphql-api-voyager-client',
            'graphQLByPoPGraphiQLSettings',
            array(
                'nonce' => \wp_create_nonce('wp_rest'),
                'endpoint' => $this->getEndpointHelpers()->getAdminConfigurableSchemaGraphQLEndpoint(),
            )
        );
    }
}

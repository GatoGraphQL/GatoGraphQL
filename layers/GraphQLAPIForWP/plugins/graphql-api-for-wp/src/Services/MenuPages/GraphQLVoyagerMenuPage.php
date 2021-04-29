<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\Services\MenuPages\AbstractMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\EnqueueReactMenuPageTrait;
use GraphQLAPI\GraphQLAPI\PluginInfo;

/**
 * Voyager page
 */
class GraphQLVoyagerMenuPage extends AbstractMenuPage
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

        // CSS
        \wp_enqueue_style(
            'graphql-api-voyager-client',
            PluginInfo::get('url') . 'assets/css/voyager-client.css',
            array(),
            PluginInfo::get('version')
        );
        \wp_enqueue_style(
            'graphql-api-voyager',
            PluginInfo::get('url') . 'assets/css/vendors/voyager.css',
            array(),
            PluginInfo::get('version')
        );

        // JS: execute them all in the footer
        $this->enqueueReactAssets(true);
        \wp_enqueue_script(
            'graphql-api-voyager',
            PluginInfo::get('url') . 'assets/js/vendors/voyager.min.js',
            array('graphql-api-react-dom'),
            PluginInfo::get('version'),
            true
        );
        \wp_enqueue_script(
            'graphql-api-voyager-client',
            PluginInfo::get('url') . 'assets/js/voyager-client.js',
            array('graphql-api-voyager'),
            PluginInfo::get('version'),
            true
        );

        // Load data into the script
        \wp_localize_script(
            'graphql-api-voyager-client',
            'graphQLByPoPGraphiQLSettings',
            array(
                'nonce' => \wp_create_nonce('wp_rest'),
                'endpoint' => $this->endpointHelpers->getAdminConfigurableSchemaGraphQLEndpoint(),
            )
        );
    }
}

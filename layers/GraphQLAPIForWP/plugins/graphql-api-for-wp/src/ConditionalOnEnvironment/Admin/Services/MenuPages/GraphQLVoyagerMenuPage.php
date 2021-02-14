<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\AbstractMenuPage;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\EnqueueReactMenuPageTrait;
use GraphQLAPI\GraphQLAPI\General\EndpointHelpers;

/**
 * Voyager page
 */
class GraphQLVoyagerMenuPage extends AbstractMenuPage
{
    use EnqueueReactMenuPageTrait;
    use GraphQLAPIMenuPageTrait;

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
     *
     * @return void
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        // CSS
        \wp_enqueue_style(
            'graphql-api-voyager-client',
            \GRAPHQL_API_URL . 'assets/css/voyager-client.css',
            array(),
            \GRAPHQL_API_VERSION
        );
        \wp_enqueue_style(
            'graphql-api-voyager',
            \GRAPHQL_API_URL . 'assets/css/vendors/voyager.css',
            array(),
            \GRAPHQL_API_VERSION
        );

        // JS: execute them all in the footer
        $this->enqueueReactAssets(true);
        \wp_enqueue_script(
            'graphql-api-voyager',
            \GRAPHQL_API_URL . 'assets/js/vendors/voyager.min.js',
            array('graphql-api-react-dom'),
            \GRAPHQL_API_VERSION,
            true
        );
        \wp_enqueue_script(
            'graphql-api-voyager-client',
            \GRAPHQL_API_URL . 'assets/js/voyager-client.js',
            array('graphql-api-voyager'),
            \GRAPHQL_API_VERSION,
            true
        );

        // Load data into the script
        \wp_localize_script(
            'graphql-api-voyager-client',
            'graphQLByPoPGraphiQLSettings',
            array(
                'nonce' => \wp_create_nonce('wp_rest'),
                'endpoint' => EndpointHelpers::getAdminGraphQLEndpoint(),
            )
        );
    }
}

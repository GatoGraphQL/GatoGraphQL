<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\ConditionalOnEnvironment\GraphiQLExplorerInAdminClient\Overrides\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\Clients\AdminGraphiQLWithExplorerClient;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\GraphiQLMenuPage as UpstreamGraphiQLMenuPage;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * GraphiQL with Explorer page
 */
class GraphiQLMenuPage extends UpstreamGraphiQLMenuPage
{
    protected function getGraphiQLWithExplorerClientHTML(): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var AdminGraphiQLWithExplorerClient
         */
        $client = $instanceManager->getInstance(AdminGraphiQLWithExplorerClient::class);
        return $client->getClientHTML();
    }

    public function print(): void
    {
        $htmlContent = $this->getGraphiQLWithExplorerClientHTML();
        // Extract the HTML inside <body>
        $matches = [];
        preg_match('/<body([^>]+)?>(.*?)<\/body>/s', $htmlContent, $matches);
        $bodyHTMLContent = $matches[2];
        // Remove all JS/CSS assets, since they are enqueued
        $bodyHTMLContent = preg_replace(
            [
                '/<link[^>]*>(.*)<\/link>"/s',
                '/<script[^>]*>(.*)<\/script>/s',
            ],
            '',
            $bodyHTMLContent
        );
        echo $bodyHTMLContent;
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     *
     * @return void
     */
    protected function enqueueGraphiQLCustomAssets(): void
    {
        // Common settings to both clients (with/out Explorer)
        $scriptSettings = array(
            'nonce' => \wp_create_nonce('wp_rest'),
            'response' => $this->getResponse(),
        );

        // Print the HTML from the Client
        $htmlContent = $this->getGraphiQLWithExplorerClientHTML();
        // Extract the JS/CSS assets, from either the <head> or the <head>
        $matches = [];
        preg_match_all('/<link[^>]+href="([^">]+)"/s', $htmlContent, $matches);
        $cssFileURLs = $matches[1];
        foreach ($cssFileURLs as $index => $cssFileURL) {
            \wp_enqueue_style(
                'graphql-api-graphiql-with-explorer-' . $index,
                $cssFileURL,
                array(),
                \GRAPHQL_API_VERSION
            );
        }
        preg_match_all('/<script[^>]+src="([^">]+)"/s', $htmlContent, $matches);
        $jsFileURLs = $matches[1];
        foreach ($jsFileURLs as $index => $jsFileURL) {
            \wp_enqueue_script(
                'graphql-api-graphiql-with-explorer-' . $index,
                $jsFileURL,
                array(),
                \GRAPHQL_API_VERSION,
                true
            );
        }

        // Override styles for the admin, so load last
        \wp_enqueue_style(
            'graphql-api-graphiql-with-explorer-client',
            \GRAPHQL_API_URL . 'assets/css/graphiql-with-explorer-client.css',
            array(),
            \GRAPHQL_API_VERSION
        );

        // Load data into the script. Because no script is enqueued since it is
        // in the body, then localize it to React
        \wp_localize_script(
            'graphql-api-graphiql-with-explorer-0',
            'graphiQLWithExplorerClientForWP',
            $scriptSettings
        );
    }
}

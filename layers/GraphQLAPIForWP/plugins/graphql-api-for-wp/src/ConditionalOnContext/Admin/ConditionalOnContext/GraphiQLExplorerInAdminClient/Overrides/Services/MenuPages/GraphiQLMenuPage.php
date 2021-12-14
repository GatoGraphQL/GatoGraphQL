<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\GraphiQLExplorerInAdminClient\Overrides\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\Clients\AdminGraphiQLWithExplorerClient;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphiQLMenuPage as UpstreamGraphiQLMenuPage;
use PoP\API\Schema\QueryInputs;

/**
 * GraphiQL with Explorer page
 */
class GraphiQLMenuPage extends UpstreamGraphiQLMenuPage
{
    private ?AdminGraphiQLWithExplorerClient $adminGraphiQLWithExplorerClient = null;

    final public function setAdminGraphiQLWithExplorerClient(AdminGraphiQLWithExplorerClient $adminGraphiQLWithExplorerClient): void
    {
        $this->adminGraphiQLWithExplorerClient = $adminGraphiQLWithExplorerClient;
    }
    final protected function getAdminGraphiQLWithExplorerClient(): AdminGraphiQLWithExplorerClient
    {
        return $this->adminGraphiQLWithExplorerClient ??= $this->instanceManager->getInstance(AdminGraphiQLWithExplorerClient::class);
    }

    protected function getGraphiQLWithExplorerClientHTML(): string
    {
        return $this->getAdminGraphiQLWithExplorerClient()->getClientHTML();
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
     */
    protected function enqueueGraphiQLCustomAssets(): void
    {
        // Common settings to both clients (with/out Explorer)
        $scriptSettings = array(
            'nonce' => \wp_create_nonce('wp_rest'),
            'response' => $this->getResponse(),
            'requestedQuery' => $this->getRequestedQuery(),
            'queryDecodeURIComponent' => true,
        );

        $mainPluginURL = (string) MainPluginManager::getConfig('url');
        $mainPluginVersion = (string) MainPluginManager::getConfig('version');

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
                $mainPluginVersion
            );
        }
        preg_match_all('/<script[^>]+src="([^">]+)"/s', $htmlContent, $matches);
        $jsFileURLs = $matches[1];
        foreach ($jsFileURLs as $index => $jsFileURL) {
            \wp_enqueue_script(
                'graphql-api-graphiql-with-explorer-' . $index,
                $jsFileURL,
                array(),
                $mainPluginVersion,
                true
            );
        }

        // Override styles for the admin, so load last
        \wp_enqueue_style(
            'graphql-api-graphiql-with-explorer-client',
            $mainPluginURL . 'assets/css/graphiql-with-explorer-client.css',
            array(),
            $mainPluginVersion
        );

        // Load data into the script. Because no script is enqueued since it is
        // in the body, then localize it to React
        \wp_localize_script(
            'graphql-api-graphiql-with-explorer-0',
            'graphiQLWithExplorerClientForWP',
            $scriptSettings
        );
    }

    /**
     * By providing the initial query via PHP, we avoid the issue
     * of it not properly decoded in JS (happening in the GraphiQL with Explorer
     * only), where the newlines are removed
     */
    protected function getRequestedQuery(): ?string
    {
        $query = $_REQUEST[QueryInputs::QUERY] ?? null;
        if (!$query) {
            return null;
        }
        // All the '"' are encoded as '\"', replace back
        return str_replace(
            '\"',
            '"',
            $query
        );
    }
}

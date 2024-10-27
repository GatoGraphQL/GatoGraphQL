<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\ConditionalOnContext\UseGraphiQLExplorer\Overrides\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\Services\Clients\AdminGraphiQLWithExplorerClient;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\GraphiQLMenuPage as UpstreamGraphiQLMenuPage;
use PoPAPI\API\Schema\QueryInputs;

/**
 * GraphiQL with Explorer page
 */
class GraphiQLMenuPage extends UpstreamGraphiQLMenuPage
{
    private ?AdminGraphiQLWithExplorerClient $adminGraphiQLWithExplorerClient = null;

    final protected function getAdminGraphiQLWithExplorerClient(): AdminGraphiQLWithExplorerClient
    {
        if ($this->adminGraphiQLWithExplorerClient === null) {
            /** @var AdminGraphiQLWithExplorerClient */
            $adminGraphiQLWithExplorerClient = $this->instanceManager->getInstance(AdminGraphiQLWithExplorerClient::class);
            $this->adminGraphiQLWithExplorerClient = $adminGraphiQLWithExplorerClient;
        }
        return $this->adminGraphiQLWithExplorerClient;
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
        $bodyHTMLContent_safe = $matches[2];
        // Remove all JS/CSS assets, since they are enqueued
        $bodyHTMLContent_safe = preg_replace(
            [
                '/<link[^>]*>(.*)<\/link>"/s',
                '/<script[^>]*>(.*)<\/script>/s',
            ],
            '',
            $bodyHTMLContent_safe
        );
        echo $bodyHTMLContent_safe;
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

        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        // Print the HTML from the Client
        $htmlContent = $this->getGraphiQLWithExplorerClientHTML();
        // Extract the JS/CSS assets
        $matches = [];
        preg_match_all('/<link[^>]+href="([^">]+)"/s', $htmlContent, $matches);
        $cssFileURLs = $matches[1];
        foreach ($cssFileURLs as $index => $cssFileURL) {
            \wp_enqueue_style(
                'gatographql-graphiql-with-explorer-' . $index,
                $cssFileURL,
                array(),
                $mainPluginVersion
            );
        }
        preg_match_all('/<script[^>]+src="([^">]+)"/s', $htmlContent, $matches);
        $jsFileURLs = $matches[1];
        foreach ($jsFileURLs as $index => $jsFileURL) {
            \wp_enqueue_script(
                'gatographql-graphiql-with-explorer-' . $index,
                $jsFileURL,
                array(),
                $mainPluginVersion,
                true
            );
        }

        // Override styles for the admin, so load last
        \wp_enqueue_style(
            'gatographql-graphiql-with-explorer-client',
            $mainPluginURL . 'assets/css/graphiql-with-explorer-client.css',
            array(),
            $mainPluginVersion
        );

        // Load data into the script. Because no script is enqueued since it is
        // in the body, then localize it to React
        \wp_localize_script(
            'gatographql-graphiql-with-explorer-0',
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
        $query = App::request(QueryInputs::QUERY) ?? App::query(QueryInputs::QUERY);
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

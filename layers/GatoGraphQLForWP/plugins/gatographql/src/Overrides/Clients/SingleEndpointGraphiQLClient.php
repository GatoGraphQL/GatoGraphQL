<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Overrides\Clients;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLClient;
use PoP\ComponentModel\Configuration\RequestHelpers;

/**
 * Serves GraphiQL v5 (graphiql-app build) for the public single endpoint at /graphiql/
 * when the build is present; otherwise falls back to legacy GraphiQL 1.5.7.
 */
class SingleEndpointGraphiQLClient extends GraphiQLClient
{
    private const GRAPHIQL_APP_VENDOR_PATH = '/vendor/graphql-by-pop/graphql-clients-for-wp/clients/graphiql-app/build';

    private ?string $graphiQLV5HTMLCache = null;

    public function getClientHTML(): string
    {
        if ($this->graphiQLV5HTMLCache !== null) {
            return $this->graphiQLV5HTMLCache;
        }

        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginPath = $mainPlugin->getPluginDir();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $manifestPath = $mainPluginPath . self::GRAPHIQL_APP_VENDOR_PATH . '/asset-manifest.json';

        if (!is_file($manifestPath)) {
            return parent::getClientHTML();
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);
        $files = $manifest['files'] ?? [];
        $entrypoints = $manifest['entrypoints'] ?? array_values(array_intersect_key(
            $files,
            array_flip(['main.js', 'main.css'])
        ));

        $workerChunks = [];
        foreach (array_keys($files) as $key) {
            if (preg_match('#^static/js/(5914|5997|8378)\.[a-f0-9]+\.chunk\.js$#', $key, $m)) {
                $workerChunks[(string) $m[1]] = $files[$key];
            }
        }

        $buildBaseURL = rtrim($mainPluginURL . 'vendor/graphql-by-pop/graphql-clients-for-wp/clients/graphiql-app/build', '/') . '/';

        $endpoint = $this->getEndpointURLOrURLPath();
        if ($endpoint === null) {
            return parent::getClientHTML();
        }
        $endpoint = RequestHelpers::addRequestParamsToEndpoint($endpoint);
        $endpoint = (string) preg_replace('#^https?:#', '', $endpoint);

        $settings = [
            'defaultQuery' => $this->getDefaultQuery(),
            'endpoint' => $endpoint,
            'response' => $this->__('Click the "Execute Query" button, or press Ctrl+Enter (Command+Enter in Mac)', 'gatographql'),
            'workerChunks' => $workerChunks,
            'buildBaseURL' => $buildBaseURL,
        ];

        $cssLinks = [];
        $jsScripts = [];
        foreach ($entrypoints as $assetPath) {
            $url = $buildBaseURL . (str_starts_with($assetPath, '/') ? $assetPath : '/' . $assetPath);
            if (str_contains($assetPath, '.css')) {
                $cssLinks[] = '<link href="' . \esc_attr($url) . '" rel="stylesheet">';
            } else {
                $jsScripts[] = $url;
            }
        }

        $inlineBuildUrl = 'window.gatographqlGraphiQLBuildURL="' . \esc_js($buildBaseURL) . '";'
            . 'var __webpack_public_path__=window.gatographqlGraphiQLBuildURL;';
        $settingsJson = json_encode($settings, \JSON_HEX_TAG | \JSON_HEX_APOS | \JSON_HEX_QUOT | \JSON_HEX_AMP);
        $inlineSettings = 'window.graphQLByPoPGraphiQLSettings=' . $settingsJson . ';';

        $html = '<!doctype html><html lang="en"><head><meta charset="utf-8"/>'
            . '<meta name="viewport" content="width=device-width,initial-scale=1"/><title>GraphiQL</title>'
            . '<style>html,body,#graphiql{width:100%;height:100%;margin:0;padding:0;overflow:hidden;}'
            . '#graphiql{display:flex;flex-direction:column;}</style>';
        foreach ($cssLinks as $link) {
            $html .= $link;
        }
        $html .= '<script>' . $inlineBuildUrl . '</script>';
        $html .= '<script>' . $inlineSettings . '</script>';
        $html .= '</head><body><noscript>You need to enable JavaScript to run this app.</noscript>';
        $html .= '<div id="graphiql"></div>';
        foreach ($jsScripts as $src) {
            $html .= '<script defer src="' . \esc_attr($src) . '"></script>';
        }
        $html .= '</body></html>';

        $this->graphiQLV5HTMLCache = $html;
        return $this->graphiQLV5HTMLCache;
    }

    private function getDefaultQuery(): string
    {
        return <<<'GRAPHQL'
            # Welcome to GraphiQL
            #
            # GraphiQL is an in-browser tool for writing, validating, and
            # testing GraphQL queries.
            #
            # Type queries into this side of the screen, and you will see intelligent
            # typeaheads aware of the current GraphQL type schema and live syntax and
            # validation errors highlighted within the text.
            #
            # GraphQL queries typically start with a "{" character. Lines that starts
            # with a # are ignored.
            #
            # Run the query (at any moment): Ctrl-Enter (or press the play button above)
            #
            GRAPHQL;
    }
}

<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use PoP\ComponentModel\Configuration\RequestHelpers;

/**
 * GraphiQL client for the single endpoint.
 * When getGraphiQLAppBuildBaseURL() returns a URL and the graphiql-app build exists
 * (manifest at path from getGraphiQLAppBuildManifestPath()), serves GraphiQL v5;
 * otherwise falls back to legacy GraphiQL 1.5.7.
 */
class GraphiQLClient extends AbstractGraphiQLClient
{
    private ?string $graphiQLV5HTMLCache = null;

    /**
     * Base URL for the graphiql-app build (with trailing slash).
     * Return null to use legacy client.
     */
    protected function getGraphiQLAppBuildBaseURL(): string
    {
        return $this->getModuleBaseURL() . '/clients/graphiql-app/build/';
    }

    /**
     * Path to graphiql-app build asset-manifest.json (package-relative).
     */
    protected function getGraphiQLAppBuildManifestPath(): string
    {
        return $this->getModuleBaseDir() . '/clients/graphiql-app/build/asset-manifest.json';
    }

    /**
     * Default query shown in the editor (override for i18n).
     */
    protected function getGraphiQLDefaultQuery(): string
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

    public function getClientHTML(): string
    {
        $buildBaseURL = $this->getGraphiQLAppBuildBaseURL();
        $manifestPath = $this->getGraphiQLAppBuildManifestPath();

        if (is_file($manifestPath)) {
            if ($this->graphiQLV5HTMLCache !== null) {
                return $this->graphiQLV5HTMLCache;
            }
            $this->graphiQLV5HTMLCache = $this->buildGraphiQLV5HTML(
                rtrim($buildBaseURL, '/') . '/',
                $manifestPath
            );
            return $this->graphiQLV5HTMLCache;
        }

        return parent::getClientHTML();
    }

    protected function buildGraphiQLV5HTML(string $buildBaseURL, string $manifestPath): string
    {
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

        $endpoint = $this->getEndpointURLOrURLPath();
        if ($endpoint === null) {
            return parent::getClientHTML();
        }
        $endpoint = RequestHelpers::addRequestParamsToEndpoint($endpoint);
        $endpoint = (string) preg_replace('#^https?:#', '', $endpoint);

        $settings = [
            'defaultQuery' => $this->getGraphiQLDefaultQuery(),
            'endpoint' => $endpoint,
            'response' => $this->__('Click the "Execute Query" button, or press Ctrl+Enter (Command+Enter in Mac)', 'default'),
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

        $inlineBuildUrl = 'window.graphqlclientsforwpGraphiQLBuildURL="' . \esc_js($buildBaseURL) . '";'
            . 'var __webpack_public_path__=window.graphqlclientsforwpGraphiQLBuildURL;';
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

        return $html;
    }

    protected function getClientRelativePath(): string
    {
        return '/clients/graphiql';
    }

    protected function getJSFilename(): string
    {
        return 'graphiql.js';
    }
}

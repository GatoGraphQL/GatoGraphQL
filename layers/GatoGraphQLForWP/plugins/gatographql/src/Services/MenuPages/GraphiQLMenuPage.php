<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GraphQLByPoP\GraphQLClientsForWP\Module as GraphQLClientsForWPModule;
use GraphQLByPoP\GraphQLClientsForWP\ModuleConfiguration as GraphQLClientsForWPModuleConfiguration;
use PoP\Root\App;

/**
 * GraphiQL page
 */
class GraphiQLMenuPage extends AbstractPluginMenuPage
{
    use EnqueueReactMenuPageTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }

    public function isServiceEnabled(): bool
    {
        $isPrivateEndpointDisabled = !$this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::PRIVATE_ENDPOINT);
        if ($isPrivateEndpointDisabled) {
            return false;
        }
        return parent::isServiceEnabled();
    }

    public function print(): void
    {
        ?>
        <div id="graphiql" class="graphiql-client">
            <p>
                <?php esc_html_e('Loading...', 'gatographql') ?>
            </p>
        </div>
        <?php
    }

    /**
     * Override, because this is the default page, so it is invoked
     * with the menu slug wp-admin/admin.php?page=gatographql,
     * and not the menu page slug wp-admin/admin.php?page=gatographql_graphiql
     */
    public function getScreenID(): string
    {
        return $this->getMenuName();
    }

    public function getMenuPageSlug(): string
    {
        return 'graphiql';
    }

    public function getMenuPageTitle(): string
    {
        return __('GraphiQL', 'gatographql');
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueGraphiQLClientAssets(): void
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        \wp_enqueue_style(
            'gatographql-graphiql-client',
            $mainPluginURL . 'assets/css/graphiql-client.css',
            array(),
            $mainPluginVersion
        );
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
        );

        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();
        $mainPluginPath = $mainPlugin->getPluginDir();

        $graphiqlAppBuildRelativePath = 'vendor/graphql-by-pop/graphql-clients-for-wp/clients/graphiql-app/build';
        $manifestPath = $mainPluginPath . '/' . $graphiqlAppBuildRelativePath . '/asset-manifest.json';
        $buildBaseURL = $mainPluginURL . $graphiqlAppBuildRelativePath;

        $manifest = json_decode((string) file_get_contents($manifestPath), true);
        $entrypoints = $manifest['entrypoints'] ?? array_values(array_intersect_key(
            $manifest['files'] ?? [],
            array_flip(['main.js', 'main.css'])
        ));

        // Monaco worker chunk paths (graphiql/setup-workers: 5914=editor, 5997=json, 8378=graphql).
        // Pass to the app so workers can be loaded with correct __webpack_public_path__ via fetch+blob.
        $workerChunks = [];
        $files = $manifest['files'] ?? [];
        foreach (array_keys($files) as $key) {
            if (preg_match('#^static/js/(5914|5997|8378)\.[a-f0-9]+\.chunk\.js$#', (string) $key, $m)) {
                $workerChunks[(string) $m[1]] = $files[$key];
            }
        }
        $buildBaseURL = rtrim($buildBaseURL, '/') . '/';

        foreach ($entrypoints as $assetPath) {
            $url = $buildBaseURL . (str_starts_with($assetPath, '/') ? $assetPath : '/' . $assetPath);
            if (str_contains($assetPath, '.css')) {
                \wp_enqueue_style(
                    'gatographql-graphiql-app-css',
                    $url,
                    array(),
                    $mainPluginVersion
                );
            } else {
                \wp_enqueue_script(
                    'gatographql-graphiql-app',
                    $url,
                    array(),
                    $mainPluginVersion,
                    true
                );
                // So chunk URLs resolve to the build folder (fixes 404s when plugin URL varies).
                // Set __webpack_public_path__ before the bundle runs so the runtime uses it (not script dir).
                \wp_add_inline_script(
                    'gatographql-graphiql-app',
                    'window.graphqlclientsforwpGraphiQLBuildURL="' . \esc_js($buildBaseURL) . '";'
                    . 'var __webpack_public_path__=window.graphqlclientsforwpGraphiQLBuildURL;',
                    'before'
                );
            }
        }

        /** @var GraphQLClientsForWPModuleConfiguration */
        $graphQLClientsForWPModuleConfiguration = App::getModule(GraphQLClientsForWPModule::class)->getConfiguration();

        // Localize to the main script (last JS enqueued)
        \wp_localize_script(
            'gatographql-graphiql-app',
            'graphQLByPoPGraphiQLSettings',
            array_merge(
                [
                    'defaultQuery' => $graphQLClientsForWPModuleConfiguration->printGraphiQLDefaultQuery()
                        ? $this->getDefaultQuery()
                        : '',
                    'endpoint' => $this->getEndpointHelpers()->getAdminGraphQLEndpoint(),
                    'workerChunks' => $workerChunks,
                    'buildBaseURL' => $buildBaseURL,
                ],
                $scriptSettings
            )
        );
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueGraphiQLClientAssets();
        $this->enqueueGraphiQLCustomAssets();
    }

    protected function getResponse(): string
    {
        return \__('Click the "Execute Query" button, or press Ctrl+Enter (Command+Enter in Mac)', 'gatographql');
    }

    protected function getDefaultQuery(): string
    {
        return <<<GRAPHQL
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
            # An example GraphQL query might look like:
            #
            #   {
            #     field(arg: "value") {
            #       subField
            #     }
            #   }
            #
            # Run the query (at any moment):
            #
            #   Ctrl-Enter (or press the play button above)
            #
            GRAPHQL;
    }
}

<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;

/**
 * GraphiQL page
 */
class GraphiQLMenuPage extends AbstractPluginMenuPage
{
    use EnqueueReactMenuPageTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
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

        \wp_enqueue_style(
            'gatographql-graphiql',
            $mainPluginURL . 'vendor/graphql-by-pop/graphql-clients-for-wp/clients/graphiql/assets/vendors/graphiql.1.5.7.min.css',
            array(),
            $mainPluginVersion
        );

        // JS: execute them all in the footer
        $this->enqueueReactAssets(true);

        \wp_enqueue_script(
            'gatographql-graphiql',
            $mainPluginURL . 'vendor/graphql-by-pop/graphql-clients-for-wp/clients/graphiql/assets/vendors/graphiql.1.5.7.min.js',
            array('gatographql-react-dom'),
            $mainPluginVersion,
            true
        );
        \wp_enqueue_script(
            'gatographql-graphiql-client',
            $mainPluginURL . 'assets/js/graphiql-client.js',
            array('gatographql-graphiql'),
            $mainPluginVersion,
            true
        );

        // Load data into the script
        \wp_localize_script(
            'gatographql-graphiql-client',
            'graphQLByPoPGraphiQLSettings',
            array_merge(
                [
                    'defaultQuery' => $this->getDefaultQuery(),
                    'endpoint' => $this->getEndpointHelpers()->getAdminGraphQLEndpoint(),
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

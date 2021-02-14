<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use GraphQLAPI\GraphQLAPI\General\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Admin\MenuPages\AbstractMenuPage;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\Clients\AdminGraphiQLWithExplorerClient;
use GraphQLAPI\GraphQLAPI\Admin\MenuPages\EnqueueReactMenuPageTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;

/**
 * GraphiQL page
 */
class GraphiQLMenuPage extends AbstractMenuPage
{
    use EnqueueReactMenuPageTrait;
    use GraphQLAPIMenuPageTrait;

    protected function useGraphiQLExplorer(): bool
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        return
            $moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER)
            && $userSettingsManager->getSetting(
                ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                ClientFunctionalityModuleResolver::OPTION_USE_IN_ADMIN_CLIENT
            );
    }

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
        if (!$this->useGraphiQLExplorer()) {
            ?>
            <div id="graphiql" class="graphiql-client">
                <p>
                    <?php echo __('Loading...', 'graphql-api') ?>
                    <!--span class="spinner is-active" style="float: none;"></span-->
                </p>
            </div>
            <?php
        } else {
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
    }

    /**
     * Override, because this is the default page, so it is invoked
     * with the menu slug wp-admin/admin.php?page=graphql_api,
     * and not the menu page slug wp-admin/admin.php?page=graphql_api_graphiql
     *
     * @return string
     */
    public function getScreenID(): string
    {
        return $this->getMenuName();
    }

    public function getMenuPageSlug(): string
    {
        return 'graphiql';
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     *
     * @return void
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $useGraphiQLExplorer = $this->useGraphiQLExplorer();

        // CSS
        \wp_enqueue_style(
            'graphql-api-graphiql-client',
            \GRAPHQL_API_URL . 'assets/css/graphiql-client.css',
            array(),
            \GRAPHQL_API_VERSION
        );

        // Common settings to both clients
        $scriptSettings = array(
            'nonce' => \wp_create_nonce('wp_rest'),
            'response' => $this->getResponse(),
        );

        if (!$useGraphiQLExplorer) {
            \wp_enqueue_style(
                'graphql-api-graphiql',
                \GRAPHQL_API_URL . 'assets/css/vendors/graphiql.min.css',
                array(),
                \GRAPHQL_API_VERSION
            );

            // JS: execute them all in the footer
            $this->enqueueReactAssets(true);

            \wp_enqueue_script(
                'graphql-api-graphiql',
                \GRAPHQL_API_URL . 'assets/js/vendors/graphiql.min.js',
                array('graphql-api-react-dom'),
                \GRAPHQL_API_VERSION,
                true
            );
            \wp_enqueue_script(
                'graphql-api-graphiql-client',
                \GRAPHQL_API_URL . 'assets/js/graphiql-client.js',
                array('graphql-api-graphiql'),
                \GRAPHQL_API_VERSION,
                true
            );

            // Load data into the script
            \wp_localize_script(
                'graphql-api-graphiql-client',
                'graphQLByPoPGraphiQLSettings',
                array_merge(
                    [
                        'defaultQuery' => $this->getDefaultQuery(),
                        'endpoint' => EndpointHelpers::getAdminGraphQLEndpoint(),
                    ],
                    $scriptSettings
                )
            );
        } else {
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

    protected function getResponse(): string
    {
        return '';
        // return \__('Click the "Execute Query" button, or press Ctrl+Enter (Command+Enter in Mac)', 'graphql-api');
    }

    protected function getDefaultQuery(): string
    {
        return <<<EOT
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

            query {
              posts(limit:3) {
                id
                title
                date(format:"d/m/Y")
                url
                author {
                  id
                  name
                  url
                }
                tags {
                  name
                }
                featuredImage {
                  src
                }
              }
            }

            EOT;
    }
}

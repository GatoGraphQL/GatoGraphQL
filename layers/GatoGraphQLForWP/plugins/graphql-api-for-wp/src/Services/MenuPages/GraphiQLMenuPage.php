<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\PluginApp;

/**
 * GraphiQL page
 */
class GraphiQLMenuPage extends AbstractPluginMenuPage
{
    use EnqueueReactMenuPageTrait;

    public function print(): void
    {
        ?>
        <div id="graphiql" class="graphiql-client">
            <p>
                <?php echo __('Loading...', 'gato-graphql') ?>
                <!--span class="spinner is-active" style="float: none;"></span-->
            </p>
        </div>
        <?php
    }

    /**
     * Override, because this is the default page, so it is invoked
     * with the menu slug wp-admin/admin.php?page=graphql_api,
     * and not the menu page slug wp-admin/admin.php?page=graphql_api_graphiql
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
     */
    protected function enqueueGraphiQLClientAssets(): void
    {
        $mainPluginURL = PluginApp::getMainPlugin()->getPluginURL();
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();

        \wp_enqueue_style(
            'gato-graphql-graphiql-client',
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

        $mainPluginURL = PluginApp::getMainPlugin()->getPluginURL();
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();

        \wp_enqueue_style(
            'gato-graphql-graphiql',
            $mainPluginURL . 'assets/css/vendors/graphiql.1.5.7.min.css',
            array(),
            $mainPluginVersion
        );

        // JS: execute them all in the footer
        $this->enqueueReactAssets(true);

        \wp_enqueue_script(
            'gato-graphql-graphiql',
            $mainPluginURL . 'assets/js/vendors/graphiql.1.5.7.min.js',
            array('gato-graphql-react-dom'),
            $mainPluginVersion,
            true
        );
        \wp_enqueue_script(
            'gato-graphql-graphiql-client',
            $mainPluginURL . 'assets/js/graphiql-client.js',
            array('gato-graphql-graphiql'),
            $mainPluginVersion,
            true
        );

        // Load data into the script
        \wp_localize_script(
            'gato-graphql-graphiql-client',
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
        return '';
        // return \__('Click the "Execute Query" button, or press Ctrl+Enter (Command+Enter in Mac)', 'gato-graphql');
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
              posts(pagination: { limit: 3 }) {
                id
                title
                date
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

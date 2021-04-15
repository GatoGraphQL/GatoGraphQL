<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\PluginInfo;

/**
 * GraphiQL page
 */
class GraphiQLMenuPage extends AbstractMenuPage
{
    use EnqueueReactMenuPageTrait;

    public function print(): void
    {
        ?>
        <div id="graphiql" class="graphiql-client">
            <p>
                <?php echo __('Loading...', 'graphql-api') ?>
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
        \wp_enqueue_style(
            'graphql-api-graphiql-client',
            PluginInfo::get('url') . 'assets/css/graphiql-client.css',
            array(),
            PluginInfo::get('version')
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

        \wp_enqueue_style(
            'graphql-api-graphiql',
            PluginInfo::get('url') . 'assets/css/vendors/graphiql.min.css',
            array(),
            PluginInfo::get('version')
        );

        // JS: execute them all in the footer
        $this->enqueueReactAssets(true);

        \wp_enqueue_script(
            'graphql-api-graphiql',
            PluginInfo::get('url') . 'assets/js/vendors/graphiql.min.js',
            array('graphql-api-react-dom'),
            PluginInfo::get('version'),
            true
        );
        \wp_enqueue_script(
            'graphql-api-graphiql-client',
            PluginInfo::get('url') . 'assets/js/graphiql-client.js',
            array('graphql-api-graphiql'),
            PluginInfo::get('version'),
            true
        );

        // Load data into the script
        \wp_localize_script(
            'graphql-api-graphiql-client',
            'graphQLByPoPGraphiQLSettings',
            array_merge(
                [
                    'defaultQuery' => $this->getDefaultQuery(),
                    'endpoint' => $this->endpointHelpers->getAdminGraphQLEndpoint(),
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

<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Constants\HTMLCodes;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphQLVoyagerMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphiQLMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\RecipesMenuPage;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

trait CommonModuleResolverTrait
{
    protected function getDefaultValueLabel(): string
    {
        return \__('Default value for the Schema Configuration', 'graphql-api');
    }

    protected function getDefaultValueDescription(): string
    {
        return sprintf(
            \__('<span class="settings-info">%s</span><br/>%s', 'graphql-api'),
            \__('This value will be used when option <code>"Default"</code> is selected in the Schema Configuration for any public endpoint.', 'graphql-api'),
            $this->getCollapsible(
                \__('The public endpoints are:', 'graphql-api') . $this->getPublicEndpointsListDescription(),
                \__('(Show public endpoints)', 'graphql-api')
            )
        );
    }

    protected function getPublicEndpointsListDescription(): string
    {
        return \__('<ul><li>Single endpoint</li><li>Custom endpoints</li><li>Persisted queries</li></ul>', 'graphql-api');
    }

    protected function getAdminClientDescription(): string
    {
        return sprintf(
            \__('%s<br/>%s', 'graphql-api'),
            \__('Same, but applied to private endpoints.', 'graphql-api'),
            $this->getCollapsible(
                \__('The private endpoints are:', 'graphql-api') . $this->getPrivateEndpointsListDescription(),
                \__('(Show private endpoints)', 'graphql-api')
            )
        );
    }

    protected function getCollapsible(
        string $content,
        ?string $showDetailsLabel = null,
    ): string {
        return sprintf(
            '<a href="#" type="button" class="collapsible">%s</a><span class="collapsible-content">%s</span>',
            $showDetailsLabel ?? \__('Show details', 'graphql-api'),
            $content
        );
    }

    protected function getPrivateEndpointsListDescription(): string
    {
        return sprintf(
            \__('<ul><li>Endpoint <code>%1$s</code> (which powers the admin\'s <a href="%2$s" target="_blank">GraphiQL%5$s</a> and <a href="%3$s" target="_blank">Interactive Schema%5$s</a> clients, and can be invoked in the WordPress editor to feed data to blocks)</li><li><a href="%4$s" target="_blank">Custom private endpoints%5$s</a> (also used to feed data to blocks, but allowing to lock its configuration via PHP hooks)</li><li>GraphQL queries executed internally (via class <code>%6$s</code> in PHP)</li></ul>', 'graphql-api'),
            ltrim(
                GeneralUtils::removeDomain($this->getEndpointHelpers()->getAdminGraphQLEndpoint()),
                '/'
            ),
            \admin_url(sprintf(
                'admin.php?page=%s',
                $this->getGraphiQLMenuPage()->getScreenID()
            )),
            \admin_url(sprintf(
                'admin.php?page=%s',
                $this->getGraphQLVoyagerMenuPage()->getScreenID()
            )),
            \admin_url(sprintf(
                'admin.php?page=%s&%s=%s',
                $this->getRecipesMenuPage()->getScreenID(),
                RequestParams::TAB,
                'defining-custom-private-endpoints'
            )),
            HTMLCodes::OPEN_IN_NEW_WINDOW,
            'GraphQLServer'
        );
    }

    protected function getOnPublicEndpointsLabel(string $label): string
    {
        return sprintf(
            '%s (on public endpoints)',
            $label
        );
    }

    protected function getPublicEndpointValueDescription(): string
    {
        return $this->getCollapsible(
            \__('This value will be used on public endpoints only; private endpoints are unrestricted.', 'graphql-api')
        );
    }

    protected function getGraphiQLMenuPage(): GraphiQLMenuPage
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphiQLMenuPage */
        return $instanceManager->getInstance(GraphiQLMenuPage::class);
    }

    protected function getGraphQLVoyagerMenuPage(): GraphQLVoyagerMenuPage
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLVoyagerMenuPage */
        return $instanceManager->getInstance(GraphQLVoyagerMenuPage::class);
    }

    protected function getRecipesMenuPage(): RecipesMenuPage
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var RecipesMenuPage */
        return $instanceManager->getInstance(RecipesMenuPage::class);
    }

    protected function getEndpointHelpers(): EndpointHelpers
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        return $instanceManager->getInstance(EndpointHelpers::class);
    }
}

<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Constants\HTMLCodes;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphQLVoyagerMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphiQLMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\RecipesMenuPage;
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
            '<span style="color: olivedrab;">%s%s</span>',
            \__('This value will be used when option <code>"Default"</code> is selected in the Schema Configuration applied to some public endpoint:', 'graphql-api'),
            $this->getPublicEndpointsListDescription()
        );
    }

    protected function getPublicEndpointsListDescription(): string
    {
        return \__('<ul><li>single endpoint</li><li>custom endpoints</li><li>persisted queries</li></ul>', 'graphql-api');
    }

    protected function getAdminClientDescription(): string
    {
        return sprintf(
            \__('%s. <br/><span style="color: olivedrab;">%s%s</span>', 'graphql-api'),
            \__('Same, but applied to private endpoints', 'graphql-api'),
            \__('This configuration will be reflected in:', 'graphql-api'),
            $this->getPrivateEndpointsListDescription()
        );
    }

    protected function getPrivateEndpointsListDescription(): string
    {
        return sprintf(
            \__('<ul><li>the admin\'s <a href="%1$s" target="_blank">GraphiQL%4$s</a> and <a href="%2$s" target="_blank">Interactive Schema%4$s</a> clients</li><li>GraphQL queries executed internally (via class <code>%5$s</code> in PHP)</li><li><a href="%3$s" target="_blank">custom private endpoints%4$s</a> (when no pre-defined configuration is provided via PHP)</li></ul>', 'graphql-api'),
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
        return sprintf(
            '<span style="color: olivedrab;">%s</span>',
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
}

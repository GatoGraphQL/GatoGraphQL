<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Constants\HTMLCodes;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphQLVoyagerMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphiQLMenuPage;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

trait CommonModuleResolverTrait
{
    protected function getDefaultValueLabel(): string
    {
        return \__('Default value for the Schema Configuration', 'graphql-api');
    }

    protected function getDefaultValueDescription(): string
    {
        return \__('This value will be used when option <code>"Default"</code> is selected in the Schema Configuration', 'graphql-api');
    }

    protected function getAdminClientDescription(): string
    {
        return sprintf(
            \__('It will be applied in the admin\'s <a href="%1$s" target="_blank">GraphiQL%3$s</a> and <a href="%2$s" target="_blank">Interactive Schema%3$s</a> clients', 'graphql-api'),
            \admin_url(sprintf(
                'admin.php?page=%s',
                $this->getGraphiQLMenuPage()->getScreenID()
            )),
            \admin_url(sprintf(
                'admin.php?page=%s',
                $this->getGraphQLVoyagerMenuPage()->getScreenID()
            )),
            HTMLCodes::OPEN_IN_NEW_WINDOW
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

    protected function getAdminClientAndConfigurationDescription(): string
    {
        return sprintf(
            '%s%s',
            $this->getAdminClientDescription(),
            \__(', and configuration in extensions', 'graphql-api')
        );
    }
}

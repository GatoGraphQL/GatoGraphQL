<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Menus;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\PluginAppGraphQLServerNames;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractMenu extends AbstractAutomaticallyInstantiatedService implements MenuInterface
{
    use BasicServiceTrait;

    /**
     * Only initialize once, for the main AppThread
     */
    public function isServiceEnabled(): bool
    {
        return App::getAppThread()->getName() === PluginAppGraphQLServerNames::STANDARD;
    }

    /**
     * Initialize the endpoints
     */
    public function initialize(): void
    {
        \add_action(
            'admin_menu',
            $this->addMenuPage(...),
            5
        );
    }
}

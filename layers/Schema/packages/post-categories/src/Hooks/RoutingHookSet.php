<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\Hooks;

use PoP\Engine\App;
use PoP\Root\Managers\ComponentManager;
use PoP\BasicService\AbstractHookSet;
use PoP\Routing\RouteHookNames;
use PoPSchema\PostCategories\Component;
use PoPSchema\PostCategories\ComponentConfiguration;

class RoutingHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addAction(
            RouteHookNames::ROUTES,
            [$this, 'registerRoutes']
        );
    }

    public function registerRoutes(array $routes): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return [
            ...$routes,
            $componentConfiguration->getPostCategoriesRoute(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\UsersWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\RoutingWP\HookNames;
use PoPSchema\Users\Component;
use PoPSchema\Users\ComponentConfiguration;

class RoutingHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addAction(
            HookNames::ROUTES,
            [$this, 'registerRoutes']
        );
    }

    public function registerRoutes(array $routes): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return [
            ...$routes,
            $componentConfiguration->getUsersRoute(),
        ];
    }
}

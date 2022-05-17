<?php

declare(strict_types=1);

namespace PoPCMSSchema\UsersWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\RootWP\Routing\HookNames;
use PoPCMSSchema\Users\Module;
use PoPCMSSchema\Users\ModuleConfiguration;

class RoutingHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addAction(
            HookNames::ROUTES,
            $this->registerRoutes(...)
        );
    }

    public function registerRoutes(array $routes): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        return [
            ...$routes,
            $moduleConfiguration->getUsersRoute(),
        ];
    }
}

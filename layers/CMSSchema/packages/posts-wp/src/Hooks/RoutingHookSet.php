<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostsWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\RootWP\Routing\HookNames;
use PoPCMSSchema\Posts\Module;
use PoPCMSSchema\Posts\ModuleConfiguration;

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
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return [
            ...$routes,
            $moduleConfiguration->getPostsRoute(),
        ];
    }
}

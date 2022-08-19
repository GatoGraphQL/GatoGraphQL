<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoriesWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\RootWP\Routing\HookNames;
use PoPCMSSchema\PostCategories\Module;
use PoPCMSSchema\PostCategories\ModuleConfiguration;

class RoutingHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addAction(
            HookNames::ROUTES,
            $this->registerRoutes(...)
        );
    }

    /**
     * @return string[]
     * @param string[] $routes
     */
    public function registerRoutes(array $routes): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return [
            ...$routes,
            $moduleConfiguration->getPostCategoriesRoute(),
        ];
    }
}

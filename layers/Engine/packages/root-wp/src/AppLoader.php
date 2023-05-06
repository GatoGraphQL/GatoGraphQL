<?php

declare(strict_types=1);

namespace PoP\RootWP;

use PoP\Root\App;
use PoP\Root\AppLoader as UpstreamAppLoader;
use PoP\RootWP\StaticHelpers\AppLoaderStaticHelpers;

class AppLoader extends UpstreamAppLoader
{
    /**
     * Override to execute logic only after all the plugins (and all the logic)
     * has been loaded.
     */
    public function bootApplicationModules(): void
    {
        foreach (AppLoaderStaticHelpers::getBootApplicationHooks() as $actionHook) {
            App::addAction(
                $actionHook,
                fn () => parent::bootApplicationModules(),
                /**
                 * Execute at the beginning, only to tell developers that,
                 * starting from these hooks on, the GraphQL server is ready
                 */
                0
            );
        }
    }
}

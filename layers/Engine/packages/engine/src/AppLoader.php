<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\Root\App;
use PoP\Root\AppLoader as UpstreamAppLoader;

class AppLoader extends UpstreamAppLoader
{
    /**
     * Trigger "beforeBoot", "boot" and "afterBoot" events on all the Components,
     * for them to execute any custom extra logic.
     * Override to execute functions on CMS events.
     */
    protected function bootApplicationForComponents(): void
    {
        // Boot all the components
        App::getComponentManager()->beforeBoot();

        App::addAction(
            'popcms:boot',
            fn() => App::getAppStateManager()->initializeAppState(),
            0
        );

        App::addAction(
            'popcms:boot',
            fn () => App::getComponentManager()->boot(),
            4
        );

        App::addAction(
            'popcms:boot',
            fn () => App::getComponentManager()->afterBoot(),
            8
        );
    }
}

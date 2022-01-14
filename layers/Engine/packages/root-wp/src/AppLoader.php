<?php

declare(strict_types=1);

namespace PoP\RootWP;

use PoP\Root\App;
use PoP\Root\AppLoader as UpstreamAppLoader;

class AppLoader extends UpstreamAppLoader
{
    /**
     * Override to execute logic on the proper WP action.
     */
    protected function bootApplicationForComponents(): void
    {
        // Boot all the components
        App::getComponentManager()->beforeBoot();

        /**
         * Find the right action, depending if we are in wp-admin or in frontend.
         *
         * Boot once it has parsed the WP_Query, so that the requested post/user/etc
         * is already processed and available. Only hook available is "wp".
         *
         * Watch out: "wp" doesn't trigger in the admin()!
         * Hence, in that case, use "wp_loaded" instead
         */
        $actionHook = \is_admin() ? 'wp_loaded' : 'wp';

        // Override when the functionality is executed
        App::addAction(
            $actionHook,
            fn() => App::getAppStateManager()->initializeAppState(),
            0
        );
        App::addAction(
            $actionHook,
            fn () => App::getComponentManager()->boot(),
            4
        );
        App::addAction(
            $actionHook,
            fn () => App::getComponentManager()->afterBoot(),
            8
        );
        // Allow to inject functionality
        App::addAction(
            $actionHook,
            fn () => App::doAction('popcms:boot'),
            10
        );
    }
}

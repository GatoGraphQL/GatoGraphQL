<?php

declare(strict_types=1);

namespace PoP\RootWP;

use PoP\Root\App;
use PoP\Root\AppLoader as UpstreamAppLoader;

class AppLoader extends UpstreamAppLoader
{
    /**
     * Override to execute logic only after all the plugins (and all the logic)
     * has been loaded.
     */
    public function bootApplicationModules(): void
    {
        /**
         * Find the right action hook to initialize the application,
         * depending if we are in wp-admin, in the WP REST API, or in the frontend.
         *
         * Boot once it has parsed the WP_Query, so that the requested post/user/etc
         * is already processed and available. For the front-end, the only hook
         * available is "wp".
         *
         * Watch out:
         *
         * - "wp" doesn't trigger in the admin() => use "wp_loaded" instead.
         * - "wp" doesn't trigger in REST => use "rest_api_init" instead.
         *
         * (Eg for the latter: when editing an ACL in the WordPress editor
         * and clicking on Update, it uses a REST call.)
         *
         * Because we don't know yet if the current request is a REST call or not
         * (must wait until hook "parse_request" for that), simply
         * load the hook for both 'rest_api_init' and 'wp', knowing
         * that only one of them will be called anyway.
         *
         * @see https://stackoverflow.com/questions/41101294/check-whether-request-is-wp-rest-api-request
         */
        $actionHooks = \is_admin() ? ['wp_loaded'] : ['rest_api_init', 'wp'];
        foreach ($actionHooks as $actionHook) {
            App::addAction(
                $actionHook,
                fn () => parent::bootApplicationModules()
            );
        }
    }
}

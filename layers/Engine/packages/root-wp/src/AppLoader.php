<?php

declare(strict_types=1);

namespace PoP\RootWP;

use PoP\Root\App;
use PoP\Root\AppLoader as UpstreamAppLoader;

use function is_admin;

class AppLoader extends UpstreamAppLoader
{
    /**
     * Override to execute logic only after all the plugins (and all the logic)
     * has been loaded.
     */
    public function bootApplicationModules(): void
    {
        foreach ($this->getBootApplicationActionHooks() as $filterHook) {
            App::addAction(
                $filterHook,
                fn () => parent::bootApplicationModules(),
                /**
                 * Execute at the beginning, only to tell developers that,
                 * starting from these hooks on, the GraphQL server is ready
                 */
                0
            );
        }
        foreach ($this->getBootApplicationFilterHooks() as $filterHook) {
            App::addFilter(
                $filterHook,
                function (mixed $value): mixed {
                    parent::bootApplicationModules();
                    return $value;
                },
                0
            );
        }
    }

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
     *
     * @return string[]
     */
    protected function getBootApplicationActionHooks(): array
    {
        return (is_admin() || (defined('WP_CLI') && constant('WP_CLI') && class_exists('WP_CLI'))) ? [AppHooks::BOOT_APP_IN_ADMIN] : [AppHooks::BOOT_APP_IN_FRONTEND];
    }

    /**
     * @return string[]
     */
    protected function getBootApplicationFilterHooks(): array
    {
        return (is_admin() || (defined('WP_CLI') && constant('WP_CLI') && class_exists('WP_CLI'))) ? [] : [AppHooks::BOOT_APP_IN_REST];
    }
}

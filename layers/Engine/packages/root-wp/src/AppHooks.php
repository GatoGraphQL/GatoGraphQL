<?php

declare(strict_types=1);

namespace PoP\RootWP;

class AppHooks
{
    /**
     * WordPress action hook to boot the App when `is_admin()`
     */
    public final const BOOT_APP_IN_ADMIN = 'wp_loaded';
    /**
     * WordPress action hook to boot the App when in the frontend
     */
    public final const BOOT_APP_IN_FRONTEND = 'wp';
    /**
     * WordPress filter hook to boot the App when in the WP REST API
     *
     * Watch out! Can't use `rest_api_init` because it is called several
     * times, including before the user is authenticated. Then,
     * the user would not be logged in for the execution of the GraphQL query.
     *
     * So we use `rest_jsonp_enabled` instead, which is called after the user
     * is authenticated.
     */
    public final const BOOT_APP_IN_REST = 'rest_jsonp_enabled';
}

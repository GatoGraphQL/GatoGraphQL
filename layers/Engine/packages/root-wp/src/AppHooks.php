<?php

declare(strict_types=1);

namespace PoP\RootWP;

class AppHooks
{
    /**
     * WordPress hook to boot the App when `is_admin()`
     */
    public final const BOOT_APP_IN_ADMIN = 'wp_loaded';
    /**
     * WordPress hook to boot the App when in the WP REST API
     */
    public final const BOOT_APP_IN_REST = 'rest_api_init';
    /**
     * WordPress hook to boot the App when in the frontend
     */
    public final const BOOT_APP_IN_FRONTEND = 'wp';
}

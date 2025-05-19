<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log\Controllers\Internal\Utilities;

use Exception;
use WP_Filesystem_Base;

use function get_filesystem_method;

/**
 * FilesystemUtil class.
 */
class FilesystemUtil
{
    /**
     * Wrapper to retrieve the class instance contained in the $wp_filesystem global, after initializing if necessary.
     *
     * @return WP_Filesystem_Base
     * @throws Exception Thrown when the filesystem fails to initialize.
     */
    public static function get_wp_filesystem(): WP_Filesystem_Base
    {
        global $wp_filesystem;

        if (! $wp_filesystem instanceof WP_Filesystem_Base) {
            $initialized = self::initialize_wp_filesystem();

            if (false === $initialized) {
                throw new Exception('The WordPress filesystem could not be initialized.');
            }
        }

        return $wp_filesystem;
    }

    /**
     * Get the WP filesystem method, with a fallback to 'direct' if no FS_METHOD constant exists and there are not FTP related options/credentials set.
     *
     * @return string|false The name of the WP filesystem method to use.
     */
    public static function get_wp_filesystem_method_or_direct()
    {
        if (function_exists('get_filesystem_method')) {
            $method = get_filesystem_method();
            if ($method) {
                return $method;
            }
        }

        return 'direct';
    }

    /**
     * Wrapper to initialize the WP filesystem with defined credentials if they are available.
     *
     * @return bool True if the $wp_filesystem global was successfully initialized.
     */
    protected static function initialize_wp_filesystem(): bool
    {
        global $wp_filesystem;

        if ($wp_filesystem instanceof WP_Filesystem_Base) {
            return true;
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';

        $method      = self::get_wp_filesystem_method_or_direct();
        $initialized = false;

        if ('direct' === $method) {
            $initialized = WP_Filesystem();
        } elseif (false !== $method) {
            // See https://core.trac.wordpress.org/changeset/56341.
            ob_start();
            $credentials = request_filesystem_credentials('');
            ob_end_clean();

            $initialized = $credentials && WP_Filesystem($credentials);
        }

        return is_null($initialized) ? false : $initialized;
    }
}

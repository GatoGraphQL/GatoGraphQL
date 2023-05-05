<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

abstract class AbstractMainPluginDowngradeContainerConfigurationService extends AbstractPluginDowngradeContainerConfigurationService
{
    public function configureContainer(): void
    {
        parent::configureContainer();

        /**
         * This constant is defined in wp-load.php, but never loaded.
         * It is read when resolving class WP_Upgrader in Plugin.php.
         * Define it here again, or otherwise Rector fails with message:
         *
         * "PHP Warning:  Use of undefined constant ABSPATH -
         * assumed 'ABSPATH' (this will throw an Error in a future version of PHP)
         * in .../gato-graphql-for-wp/vendor/wordpress/wordpress/wp-admin/includes/class-wp-upgrader.php
         * on line 13"
         */
        /** Define ABSPATH as this file's directory */
        if (!defined('ABSPATH')) {
            define('ABSPATH', $this->pluginDir . '/vendor/wordpress/wordpress/');
        }
    }
}

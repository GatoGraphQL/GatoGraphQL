<?php

declare(strict_types=1);

namespace PoP\Root\Dotenv;

use PoP\Root\App;
use Symfony\Component\Dotenv\Dotenv;

class DotenvBuilderFactory
{
    /**
     * Initialize the file location to the document root
     */
    public static function init(): void
    {
        // Set the folder where to find .env files through an environment constant.
        // If not set, use "/config" in the root directory
        $envConfigFolder = getenv('ENV_CONFIG_FOLDER');
        if (!$envConfigFolder) {
            $envConfigFolder = App::server('DOCUMENT_ROOT', '') . '/config';
        }

        // If the file location has been set, then load the environment variables from .env files stored there
        if (file_exists($envConfigFolder . '/.env')) {
            $dotenv = new Dotenv();
            $dotenv->loadEnv($envConfigFolder . '/.env');
        }
    }
}

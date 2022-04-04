<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Info;

use PoP\Root\Environment;

class ApplicationInfo implements ApplicationInfoInterface
{
    private readonly string $version;

    /**
     * Inject the version from the environment
     */
    public function __construct()
    {
        $this->version = Environment::getApplicationVersion() ?? '';
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Info;

use PoP\Root\Environment;

class ApplicationInfo implements ApplicationInfoInterface
{
    private string $version;

    public function __construct(string $version)
    {
        $this->version = $version;

        // If the version is provided by environment var, then use that one
        if ($version = Environment::getApplicationVersion()) {
            $this->version = $version;
        }
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}

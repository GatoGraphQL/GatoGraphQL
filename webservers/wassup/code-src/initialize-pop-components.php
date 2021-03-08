<?php

declare(strict_types=1);

use PoP\Engine\AppLoader;

// Set the Component configuration
AppLoader::addComponentClassesToInitialize([
    \PoPSitesWassup\Wassup\Component::class,
]);


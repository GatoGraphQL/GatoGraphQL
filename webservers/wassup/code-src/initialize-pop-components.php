<?php

declare(strict_types=1);

use PoP\Engine\App;

// Set the Component configuration
App::getAppLoader()->addComponentClassesToInitialize([
    \PoPSitesWassup\Wassup\Component::class,
]);


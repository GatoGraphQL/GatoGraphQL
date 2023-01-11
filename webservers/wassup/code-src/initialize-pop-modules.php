<?php

declare(strict_types=1);

use PoP\Root\App;

App::stockAndInitializeModuleClasses([
    \PoPSitesWassup\Wassup\Module::class,
]);


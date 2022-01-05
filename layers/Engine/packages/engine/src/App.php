<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\Root\App as UpstreamApp;
use PoP\Root\AppLoader as UpstreamAppLoader;

/**
 * Single class hosting all the top-level instances to run the application
 */
class App extends UpstreamApp
{
    /**
     * Override the AppLoader with the one from Engine
     */
    protected static function createAppLoader(): UpstreamAppLoader
    {
        return new AppLoader();
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\Root\App;
use PoP\ComponentModel\Constants\Outputs;

class ApplicationStateHelperService implements ApplicationStateHelperServiceInterface
{
    public function doingJSON(): bool
    {
        return App::getState('output') == Outputs::JSON;
    }
}

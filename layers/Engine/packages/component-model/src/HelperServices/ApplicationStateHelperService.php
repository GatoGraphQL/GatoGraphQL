<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\State\ApplicationState;

class ApplicationStateHelperService implements ApplicationStateHelperServiceInterface
{
    public function doingJSON(): bool
    {
        $vars = ApplicationState::getVars();
        return $vars['output'] == \PoP\ComponentModel\Constants\Outputs::JSON;
    }
}

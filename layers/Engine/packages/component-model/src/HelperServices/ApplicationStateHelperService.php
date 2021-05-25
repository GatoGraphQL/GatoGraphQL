<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\State\ApplicationState;

class ApplicationStateHelperService implements ApplicationStateHelperServiceInterface
{
    function doingJson(): bool
    {
        $vars = ApplicationState::getVars();
        return $vars['output'] == \PoP\ComponentModel\Constants\Outputs::JSON;
        // return isset($_REQUEST[\PoP\ComponentModel\Constants\Params::OUTPUT]) && $_REQUEST[\PoP\ComponentModel\Constants\Params::OUTPUT] == \PoP\ComponentModel\Constants\Outputs::JSON;
    }
}

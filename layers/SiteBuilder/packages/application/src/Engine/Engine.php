<?php

declare(strict_types=1);

namespace PoP\Application\Engine;

use PoPAPI\API\Engine\RemoveEntryModuleFromOutputEngineTrait;
use PoP\ConfigurationComponentModel\Engine\Engine as UpstreamEngine;

class Engine extends UpstreamEngine
{
    use RemoveEntryModuleFromOutputEngineTrait;
}

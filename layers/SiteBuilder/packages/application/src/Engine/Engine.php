<?php

declare(strict_types=1);

namespace PoP\Application\Engine;

use PoP\API\Engine\RemoveEntryModuleFromOutputEngineTrait;
use PoP\ConfigurationComponentModel\Engine\Engine as UpstreamEngine;

class Engine extends UpstreamEngine
{
    use RemoveEntryModuleFromOutputEngineTrait;
}

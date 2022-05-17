<?php

declare(strict_types=1);

namespace PoP\Application\Engine;

use PoPAPI\API\Engine\RemoveEntryComponentFromOutputEngineTrait;
use PoP\ConfigurationComponentModel\Engine\Engine as UpstreamEngine;

class Engine extends UpstreamEngine
{
    use RemoveEntryComponentFromOutputEngineTrait;
}

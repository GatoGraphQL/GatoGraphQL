<?php

declare(strict_types=1);

namespace PoP\Engine\Engine;

use PoP\ComponentModel\Engine\EngineInterface as UpstreamEngineInterface;

interface EngineInterface extends UpstreamEngineInterface
{
    public function outputResponse(): void;
}

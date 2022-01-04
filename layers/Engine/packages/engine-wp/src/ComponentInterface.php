<?php

declare(strict_types=1);

namespace PoP\EngineWP;

use PoP\Root\Component\ComponentInterface as UpstreamComponentInterface;

/**
 * Initialize component
 */
interface ComponentInterface extends UpstreamComponentInterface
{
    public function getTemplatesDir(): string;
}

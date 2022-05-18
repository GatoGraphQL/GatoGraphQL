<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Engine;

use PoP\ComponentModel\Engine\EngineInterface as UpstreamEngineInterface;

interface EngineInterface extends UpstreamEngineInterface
{
    public function getModuleSettings(array $componentVariation, $model_props, array &$props);
    public function maybeRedirectAndExit(): void;
}

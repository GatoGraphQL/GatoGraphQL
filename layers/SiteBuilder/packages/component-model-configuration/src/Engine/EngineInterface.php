<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Engine;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Engine\EngineInterface as UpstreamEngineInterface;

interface EngineInterface extends UpstreamEngineInterface
{
    public function getComponentSettings(Component $component, $model_props, array &$props);
    public function maybeRedirectAndExit(): void;
}

<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Engine;

interface EngineInterface extends \PoP\ComponentModel\Engine\EngineInterface
{
    public function getModuleSettings(array $module, $model_props, array &$props);
    public function maybeRedirectAndExit(): void;
}

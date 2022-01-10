<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;

interface AppInterface
{
    public static function initializeComponentModel(
        ?MutationResolutionManagerInterface $MutationResolutionManager = null,
    ): void;

    public static function getMutationResolutionManager(): MutationResolutionManagerInterface;
}

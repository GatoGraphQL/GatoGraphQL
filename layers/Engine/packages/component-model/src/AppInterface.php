<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\MutationResolution\MutationResolutionStoreInterface;

interface AppInterface
{
    public static function initializeComponentModel(
        ?MutationResolutionStoreInterface $MutationResolutionStore = null,
    ): void;

    public static function getMutationResolutionStore(): MutationResolutionStoreInterface;
}

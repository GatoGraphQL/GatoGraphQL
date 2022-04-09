<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema;

use GraphQLAPI\WPFakerSchema\State\MockDataStore;
use PoP\ComponentModel\AppInterface as UpstreamAppInterface;

interface AppInterface extends UpstreamAppInterface
{
    public static function initializeMockDataStore(
        ?MockDataStore $mockDataStore = null,
    ): void;
}

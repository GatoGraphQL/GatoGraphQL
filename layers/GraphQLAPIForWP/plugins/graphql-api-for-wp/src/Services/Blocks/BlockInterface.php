<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use PoP\Root\Services\AutomaticallyInstantiatedServiceInterface;

interface BlockInterface extends AutomaticallyInstantiatedServiceInterface
{
    /**
     * The block full name: namespace/blockName
     */
    public function getBlockFullName(): string;
}

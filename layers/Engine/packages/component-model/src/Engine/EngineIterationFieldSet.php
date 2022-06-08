<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ComponentFieldInterface;
use SplObjectStorage;

class EngineIterationFieldSet
{
    /**
     * @param ComponentFieldInterface[] $direct
     */
    public function __construct(
        public array $direct = [],
        public SplObjectStorage $conditional = new SplObjectStorage(),
    ) {        
    }
}

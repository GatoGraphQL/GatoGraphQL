<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

class EngineIterationFieldSet
{
    /**
     * @param FieldInterface[] $direct
     */
    public function __construct(
        public array $direct,
        public SplObjectStorage $conditional,
    ) {        
    }
}

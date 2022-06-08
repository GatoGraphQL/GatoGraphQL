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
        public readonly array $direct = [],
        public readonly SplObjectStorage $conditional = new SplObjectStorage(),
    ) {        
    }

    /**
     * @param ComponentFieldInterface[] $directComponentFields
     */
    public function addDirectComponentFields(array $directComponentFields): void
    {
        $this->direct = array_values(array_unique(array_merge(
            $this->direct,
            $directComponentFields
        )));
    }

    public function addConditionalComponentFields(SplObjectStorage $conditionalComponentFields): void
    {
        $this->conditional->addAll($conditionalComponentFields);
    }
}

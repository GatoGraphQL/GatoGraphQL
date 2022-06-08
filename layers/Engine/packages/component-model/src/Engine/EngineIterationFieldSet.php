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
        public array $direct = [],
        public SplObjectStorage $conditional = new SplObjectStorage(),
    ) {        
    }

    /**
     * @param FieldInterface[] $directComponentFields
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

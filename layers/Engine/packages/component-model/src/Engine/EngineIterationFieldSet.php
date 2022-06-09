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
     * @param FieldInterface[] $directFields
     */
    public function addDirectFields(array $directFields): void
    {
        $this->direct = array_values(array_unique(array_merge(
            $this->direct,
            $directFields
        )));
    }

    public function addConditionalFields(SplObjectStorage $conditionalFields): void
    {
        $this->conditional->addAll($conditionalFields);
    }
}

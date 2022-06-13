<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

class EngineIterationFieldSet
{
    /**
     * @param FieldInterface[] $direct
     * @param SplObjectStorage<FieldInterface,FieldInterface[]> $conditionalFields
     */
    public function __construct(
        public array $direct = [],
        public SplObjectStorage $conditionalFields = new SplObjectStorage(),
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

    /**
     * @param FieldInterface[] $conditionalFields
     */
    public function addConditionalFields(FieldInterface $conditionField, array $conditionalFields): void
    {
        $this->conditionalFields[$conditionField] = array_values(array_unique(array_merge(
            $this->conditionalFields[$conditionField] ?? [],
            $conditionalFields
        )));
    }
}

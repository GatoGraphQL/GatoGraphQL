<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

class EngineIterationFieldSet
{
    /**
     * @param FieldInterface[] $fields
     * @param SplObjectStorage<FieldInterface,FieldInterface[]> $conditionalFields
     */
    public function __construct(
        public array $fields = [],
        public SplObjectStorage $conditionalFields = new SplObjectStorage(),
    ) {
    }

    /**
     * @param FieldInterface[] $fields
     */
    public function addFields(array $fields): void
    {
        $this->fields = array_values(array_unique(array_merge(
            $this->fields,
            $fields
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

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
        $this->fields = $this->mergeFieldListsByUniqueID($this->fields, $fields);
    }

    /**
     * @param FieldInterface[] $conditionalFields
     */
    public function addConditionalFields(FieldInterface $conditionField, array $conditionalFields): void
    {
        $this->conditionalFields[$conditionField] = $this->mergeFieldListsByUniqueID(
            $this->conditionalFields[$conditionField] ?? [],
            $conditionalFields
        );
    }

    /**
     * Deduplicated union of two `FieldInterface` lists, keyed by
     * `getUniqueID()`. Avoids `array_unique`'s implicit `__toString`
     * cast (which calls `getUniqueID()` per comparison) on a hot path.
     * Preserves first-occurrence-wins semantic.
     *
     * @param FieldInterface[] $existing
     * @param FieldInterface[] $additional
     * @return FieldInterface[]
     */
    private function mergeFieldListsByUniqueID(array $existing, array $additional): array
    {
        $fieldsByUniqueID = [];
        foreach ($existing as $field) {
            $fieldUniqueID = $field->getUniqueID();
            if (!isset($fieldsByUniqueID[$fieldUniqueID])) {
                $fieldsByUniqueID[$fieldUniqueID] = $field;
            }
        }
        foreach ($additional as $field) {
            $fieldUniqueID = $field->getUniqueID();
            if (!isset($fieldsByUniqueID[$fieldUniqueID])) {
                $fieldsByUniqueID[$fieldUniqueID] = $field;
            }
        }
        return array_values($fieldsByUniqueID);
    }
}

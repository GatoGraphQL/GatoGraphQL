<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

class EngineIterationFieldSet
{
    /**
     * Cached `<uniqueID,true>` set for `$fields`, paired with the
     * `count` at the time the set was last refreshed.
     *
     * `$fields` is a public property so callers can append directly
     * (e.g. `AbstractRelationalTypeResolver` does
     * `$idFieldSet[$id]->fields[] = $field`). The count check picks
     * up any such external append on the next `addFields` call.
     * It does not catch a delete-and-replace at the same length, but
     * `$fields` is append-only across the codebase.
     *
     * @var array<string,true>
     */
    private array $cachedFieldUniqueIDsSet = [];
    private int $cachedFieldsCount = -1;

    /**
     * Per-condition-field cache for `$conditionalFields`, keyed by the
     * condition `FieldInterface`.
     *
     * @var SplObjectStorage<FieldInterface,array{0:array<string,true>,1:int}>|null
     */
    private ?SplObjectStorage $cachedConditionalFieldUniqueIDsByConditionField = null;

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
        if ($this->cachedFieldsCount !== count($this->fields)) {
            $set = [];
            foreach ($this->fields as $existingField) {
                $set[$existingField->getUniqueID()] = true;
            }
            $this->cachedFieldUniqueIDsSet = $set;
            $this->cachedFieldsCount = count($this->fields);
        }
        foreach ($fields as $field) {
            $uniqueID = $field->getUniqueID();
            if (isset($this->cachedFieldUniqueIDsSet[$uniqueID])) {
                continue;
            }
            $this->cachedFieldUniqueIDsSet[$uniqueID] = true;
            $this->fields[] = $field;
            $this->cachedFieldsCount++;
        }
    }

    /**
     * @param FieldInterface[] $conditionalFields
     */
    public function addConditionalFields(FieldInterface $conditionField, array $conditionalFields): void
    {
        if ($this->cachedConditionalFieldUniqueIDsByConditionField === null) {
            /** @var SplObjectStorage<FieldInterface,array{0:array<string,true>,1:int}> */
            $cache = new SplObjectStorage();
            $this->cachedConditionalFieldUniqueIDsByConditionField = $cache;
        }
        $cache = $this->cachedConditionalFieldUniqueIDsByConditionField;
        /** @var FieldInterface[] */
        $existing = $this->conditionalFields[$conditionField] ?? [];
        /** @var array{0:array<string,true>,1:int}|null */
        $cached = $cache[$conditionField] ?? null;
        if ($cached === null || $cached[1] !== count($existing)) {
            $set = [];
            foreach ($existing as $existingField) {
                $set[$existingField->getUniqueID()] = true;
            }
        } else {
            $set = $cached[0];
        }
        $merged = $existing;
        $countAdded = 0;
        foreach ($conditionalFields as $field) {
            $uniqueID = $field->getUniqueID();
            if (isset($set[$uniqueID])) {
                continue;
            }
            $set[$uniqueID] = true;
            $merged[] = $field;
            $countAdded++;
        }
        if ($countAdded > 0) {
            $this->conditionalFields[$conditionField] = $merged;
        }
        $cache[$conditionField] = [$set, count($merged)];
    }
}

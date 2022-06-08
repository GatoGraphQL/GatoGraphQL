<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

class FieldHelpers
{
    /**
     * Extracts all the deep conditional fields as an array of unique elements
     *
     * @return FieldInterface[]
     */
    public static function extractConditionalFields(EngineIterationFieldSet $dataFields): array
    {
        $conditionalFields = [];
        $heap = $dataFields->conditional;
        while ($heap->count() > 0) {
            // Obtain and remove first element (the conditionField) from the heap
            $heap->rewind();
            /** @var FieldInterface */
            $conditionalField = $heap->current();
            /** @var SplObjectStorage */
            $fieldDependents = $heap[$conditionalField];
            $heap->detach($conditionalField);

            // Add the conditionField to the array
            $conditionalFields[] = $conditionalField;

            // Add all conditionalFields to the heap
            $heap->addAll($fieldDependents);
        }
        return array_unique($conditionalFields);
    }
}

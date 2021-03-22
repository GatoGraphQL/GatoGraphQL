<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

class FieldHelpers
{
    /**
     * Extracts all the deep conditional fields as an array of unique elements
     */
    public static function extractConditionalFields(array $dataFields): array
    {
        $conditionalFields = [];
        $heap = $dataFields['conditional'] ?? [];
        while (!empty($heap)) {
            // Obtain and remove first element (the conditionField) from the heap
            reset($heap);
            $key = key($heap);
            $keyDataitems = $heap[$key];
            unset($heap[$key]);

            // Add the conditionField to the array
            $conditionalFields[] = $key;

            // Add all conditionalFields to the heap
            $heap = array_merge(
                $heap,
                $keyDataitems
            );
        }
        return array_unique($conditionalFields);
    }
}

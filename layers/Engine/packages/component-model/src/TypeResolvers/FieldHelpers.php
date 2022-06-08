<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ComponentFieldInterface;

class FieldHelpers
{
    /**
     * Extracts all the deep conditional fields as an array of unique elements
     *
     * @return ComponentFieldInterface[]
     */
    public static function extractConditionalFields(EngineIterationFieldSet $dataFields): array
    {
        // @todo Review this function!!!
        $conditionalFields = [];
        foreach ($dataFields->conditional as $field) {
            /** @var FieldInterface $field */
            $componentFields = $dataFields->conditional[$field];
            /** @var ComponentFieldInterface[] $componentFields */
            $conditionalFields = array_merge(
                $conditionalFields,
                $componentFields
            );
        }
        return array_unique($conditionalFields);
        // $conditionalFields = [];
        // $heap = $dataFields['conditional'] ?? [];
        // while (!empty($heap)) {
        //     // Obtain and remove first element (the conditionField) from the heap
        //     reset($heap);
        //     $key = key($heap);
        //     $keyDataitems = $heap[$key];
        //     unset($heap[$key]);

        //     // Add the conditionField to the array
        //     $conditionalFields[] = $key;

        //     // Add all conditionalFields to the heap
        //     $heap = array_merge(
        //         $heap,
        //         $keyDataitems
        //     );
        // }
        // return array_unique($conditionalFields);
    }
}

<?php

declare(strict_types=1);

namespace PoP\SiteBuilderAPI\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataProperties;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ComponentFieldNodeInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\FieldQuery\QuerySyntax;
use PoP\SiteBuilderAPI\Helpers\APIUtils;
use PoPAPI\API\Schema\QueryInputs;

trait AddAPIQueryToSourcesComponentProcessorTrait
{
    public function addAPIQueryToSources(array $sources, Component $component, array &$props): array
    {
        if (!$sources) {
            return [];
        }
        $flattened_datafields = $this->getDatasetComponentTreeSectionFlattenedDataProperties($component, $props);
        $apiFields = [];
        $heap = [
            '' => [&$flattened_datafields],
        ];
        while (!empty($heap)) {
            // Obtain and remove first element from the heap
            reset($heap);
            $key = key($heap);
            $key_dataitems = $heap[$key];
            unset($heap[$key]);
            foreach ($key_dataitems as &$key_data) {
                // If there are data fields, add them separated by "|"
                // If not, and we're inside a subcomponent, there is no need to add the subcomponent's key alone, since the engine already includes this field as a data-field (so it was added in the previous iteration)
                if ($key_datafields = $key_data[DataProperties::DIRECT_COMPONENT_FIELD_NODES]) {
                    // Make sure the fields are not repeated, and no empty values
                    $apiFields[] = $key . implode(
                        QuerySyntax::SYMBOL_FIELDPROPERTIES_SEPARATOR,
                        array_map(
                            fn (ComponentFieldNodeInterface $componentFieldNode) => $componentFieldNode->getField()->asFieldOutputQueryString(),
                            array_values(array_unique(array_filter($key_datafields)))
                        )
                    );
                }

                // If there are subcomponents, add them into the heap
                if ($key_data[DataProperties::SUBCOMPONENTS] ?? null) {
                    foreach ($key_data[DataProperties::SUBCOMPONENTS] as $subcomponent_key => &$subcomponent_data) {
                        // Add the previous key, generating a path
                        $heap[$key . $subcomponent_key . QuerySyntax::SYMBOL_RELATIONALFIELDS_NEXTLEVEL][] = &$subcomponent_data;
                    }
                }
            }
        }

        if ($apiFields) {
            return array_map(
                function ($source) use ($apiFields) {
                    return
                        GeneralUtils::addQueryArgs(
                            [
                                QueryInputs::QUERY => implode(
                                    QuerySyntax::SYMBOL_QUERYFIELDS_SEPARATOR,
                                    $apiFields
                                ),
                            ],
                            APIUtils::getEndpoint(
                                $source,
                                [
                                    DataOutputItems::COMPONENT_DATA,
                                    DataOutputItems::DATABASES,
                                    DataOutputItems::META,
                                ]
                            )
                        );
                },
                $sources
            );
        }
        return $sources;
    }
}

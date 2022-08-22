<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

abstract class AbstractIndividualControlGraphQLQueryConfigurator extends AbstractGraphQLQueryConfigurator
{
    /**
     * Create a service configuration entry comprising a field and its value,
     * adding an individual schema mode for access control.
     * It returns a single array (or null)
     *
     * @return array<mixed[]> The list of entries, where an entry is an array
     */
    protected function getIndividualControlEntriesFromField(string $selectedField, mixed $value, ?string $schemaMode): array
    {
        $entriesFromField = $this->getEntriesFromField($selectedField, $value);
        // Attach the schemaMode to all elements in the array
        if (!is_null($schemaMode)) {
            foreach ($entriesFromField as &$entryFromField) {
                $entryFromField[] = $schemaMode;
            }
        }
        return $entriesFromField;
    }
    /**
     * Create the service configuration entries comprising a directive and its value,
     * adding an individual schema mode for access control.
     * It returns an array of arrays (or null)
     *
     * @return array<mixed[]> The list of entries, where an entry is an array
     */
    protected function getIndividualControlEntriesFromDirective(string $selectedDirective, mixed $value, ?string $schemaMode): ?array
    {
        $entriesForDirective = $this->getEntriesFromDirective($selectedDirective, $value);
        if (!is_null($entriesForDirective) && !is_null($schemaMode)) {
            foreach ($entriesForDirective as &$entry) {
                $entry[] = $schemaMode;
            }
        }
        return $entriesForDirective;
    }
}

<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Overrides\DataStructureFormatters;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\Component;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use PoP\ComponentModel\Feedback\Tokens;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter as UpstreamGraphQLDataStructureFormatter;

/**
 * Change the properties printed for the standard GraphQL response:
 *
 * - extension "entityDBKey" is renamed as "type"
 * - extension "fields" (or "field" if there's one item) instead of "path",
 *   because there are no composable fields
 * - move "location" up from under "extensions"
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class GraphQLDataStructureFormatter extends UpstreamGraphQLDataStructureFormatter
{
    /**
     * Indicate if to add entry "extensions" as a top-level entry
     */
    protected function addTopLevelExtensionsEntryToResponse(): bool
    {
        if (App::getState('standard-graphql')) {
            /** @var ComponentConfiguration */
            $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
            return $componentConfiguration->enableProactiveFeedback();
        }
        return parent::addTopLevelExtensionsEntryToResponse();
    }

    /**
     * Change properties for GraphQL.
     *
     * Rename the fields to the most appropriate name:
     *
     *   - field
     *   - directive
     *   - fields <= baseline
     */
    protected function addFieldOrDirectiveEntryToExtensions(array &$extensions, array $item): void
    {
        // Single field
        $fields = $item[Tokens::PATH] ?? [];
        if (count($fields) == 1) {
            $extensions['field'] = $fields[0];
            return;
        }
        // Two fields: it may be a directive
        if (count($fields) == 2) {
            $maybeField = $fields[0];
            $maybeDirective = $fields[1];
            $maybeFieldDirectives = array_map(
                [$this->getFieldQueryInterpreter(), 'convertDirectiveToFieldDirective'],
                $this->getFieldQueryInterpreter()->getDirectives($maybeField)
            );
            // Find out if the directive is contained in the field
            if (in_array($maybeDirective, $maybeFieldDirectives)) {
                $extensions['directive'] = $maybeDirective;
                return;
            }
        }
        // Many fields
        $extensions['fields'] = $fields;
    }
    /**
     * Convert the argumentPath from array to string.
     *
     * The field or directive argument name is appended ":", and input fields
     * are separated with ".":
     *
     *   ['filter'] => 'filter:'
     *   ['filter', 'dateQuery'] => 'filter:dateQuery
     *   ['filter', 'dateQuery', 'relation'] => 'filter:dateQuery.relation
     *
     * @param array<string,mixed> $extensions
     * @return array<string,mixed>
     */
    protected function reformatExtensions(array $extensions): array
    {
        $extensions = parent::reformatExtensions($extensions);
        if (App::getState('standard-graphql')) {
            if (!empty($extensions[Tokens::ARGUMENT_PATH])) {
                // The first element is the field or directive argument name
                $fieldOrDirectiveName = array_shift($extensions[Tokens::ARGUMENT_PATH]);
                $extensions[Tokens::ARGUMENT_PATH] = sprintf(
                    '%s:%s',
                    $fieldOrDirectiveName,
                    implode('.', $extensions[Tokens::ARGUMENT_PATH])
                );
            }
        }
        return $extensions;
    }

    /**
     * Change properties for GraphQL
     */
    protected function getDBEntryExtensions(string $dbKey, int | string $id, array $item): array
    {
        if (App::getState('standard-graphql')) {
            $extensions = [
                'type' => $dbKey,
                'id' => $id,
            ];
            $this->addFieldOrDirectiveEntryToExtensions($extensions, $item);
            return $extensions;
        }
        return parent::getDBEntryExtensions($dbKey, $id, $item);
    }

    /**
     * Change properties for GraphQL
     */
    protected function getSchemaEntryExtensions(string $dbKey, array $item): array
    {
        if (App::getState('standard-graphql')) {
            $extensions = [
                'type' => $dbKey,
            ];
            $this->addFieldOrDirectiveEntryToExtensions($extensions, $item);
            return $extensions;
        }
        return parent::getSchemaEntryExtensions($dbKey, $item);
    }
    /**
     * Override the parent function, to place the locations from outside extensions
     */
    protected function getQueryEntry(string $message, array $extensions): array
    {
        $entry = [
            'message' => $message,
        ];
        // Add the "location" directly, not under "extensions"
        if ($location = $extensions['location'] ?? null) {
            unset($extensions['location']);
            $entry['location'] = $location;
        }
        // if ($this->addTopLevelExtensionsEntryToResponse()) {
        if (
            $extensions = array_merge(
                $this->getQueryEntryExtensions(),
                $extensions
            )
        ) {
            $entry['extensions'] = $extensions;
        };
        // }
        return $entry;
    }

    /**
     * Change properties for GraphQL
     */
    protected function getQueryEntryExtensions(): array
    {
        if (App::getState('standard-graphql')) {
            // Do not print "type" => "query"
            return [];
        }
        return parent::getQueryEntryExtensions();
    }
}

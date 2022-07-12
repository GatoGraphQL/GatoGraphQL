<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Overrides\DataStructureFormatters;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use PoP\ComponentModel\Feedback\Tokens;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter as UpstreamGraphQLDataStructureFormatter;

/**
 * Change the properties printed for the standard GraphQL response:
 *
 * - extension "entityTypeOutputKey" is renamed as "type"
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->enableProactiveFeedback();
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

        if (!empty($extensions[Tokens::ARGUMENT_PATH])) {
            // The first element is the field or directive argument name
            $fieldOrDirectiveName = array_shift($extensions[Tokens::ARGUMENT_PATH]);
            $extensions[Tokens::ARGUMENT_PATH] = sprintf(
                '%s:%s',
                $fieldOrDirectiveName,
                implode('.', $extensions[Tokens::ARGUMENT_PATH])
            );
        }

        return $extensions;
    }

    /**
     * Change properties for GraphQL
     */
    protected function getObjectEntryExtensions(string $typeOutputKey, int | string $id, array $item): array
    {
        return [
            'type' => $typeOutputKey,
            'id' => $id,
            'path' => $item[Tokens::PATH],
        ];
    }

    /**
     * Change properties for GraphQL
     */
    protected function getSchemaEntryExtensions(string $typeOutputKey, array $item): array
    {
        return [
            'type' => $typeOutputKey,
            'path' => $item[Tokens::PATH],
        ];
    }
}

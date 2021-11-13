<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLQuery\ComponentConfiguration;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\State\ApplicationState;

class Directive extends AbstractSchemaDefinitionReferenceObject
{
    use HasArgsSchemaDefinitionReferenceTrait;

    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);

        $this->initArgs($fullSchemaDefinition, $schemaDefinitionPath);
    }

    public function getName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::NAME];
    }

    public function getDescription(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::DESCRIPTION] ?? null;
    }

    public function getLocations(): array
    {
        $directives = [];
        $directiveKind = $this->schemaDefinition[SchemaDefinition::DIRECTIVE_KIND];
        $vars = ApplicationState::getVars();
        /**
         * There are 3 cases for adding the "Query" type locations:
         * 1. When the type is "Query"
         * 2. When the type is "Schema" and we are editing the query on the back-end (as to replace the lack of SDL)
         * 3. When the type is "Indexing" and composable directives are enabled
         */
        if (
            $directiveKind === DirectiveKinds::QUERY
            || ($directiveKind === DirectiveKinds::SCHEMA && isset($vars['edit-schema']) && $vars['edit-schema'])
            || ($directiveKind === DirectiveKinds::INDEXING && ComponentConfiguration::enableComposableDirectives())
        ) {
            // Same DirectiveLocations as used by "@skip": https://graphql.github.io/graphql-spec/draft/#sec--skip
            $directives = array_merge(
                $directives,
                [
                    DirectiveLocations::FIELD,
                    DirectiveLocations::FRAGMENT_SPREAD,
                    DirectiveLocations::INLINE_FRAGMENT,
                ]
            );
        }
        if ($directiveKind === DirectiveKinds::SCHEMA) {
            $directives = array_merge(
                $directives,
                [
                    DirectiveLocations::FIELD_DEFINITION,
                ]
            );
        }
        return $directives;
    }

    public function isRepeatable(): bool
    {
        return $this->schemaDefinition[SchemaDefinition::DIRECTIVE_IS_REPEATABLE];
    }

    public function getExtensions(): array
    {
        $extensions = $this->schemaDefinition[SchemaDefinition::EXTENSIONS] ?? [];
        if ($version = $this->schemaDefinition[SchemaDefinition::VERSION] ?? null) {
            $extensions[SchemaDefinition::VERSION] = $version;
        }
        $extensions[SchemaDefinition::DIRECTIVE_KIND] = $this->schemaDefinition[SchemaDefinition::DIRECTIVE_KIND];
        return $extensions;
    }
}

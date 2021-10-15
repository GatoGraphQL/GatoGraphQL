<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\API\Schema\SchemaDefinitionHelpers;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class DirectiveSchemaDefinitionProvider extends AbstractSchemaDefinitionProvider implements SchemaDefinitionProviderInterface
{
    public function __construct(
        protected DirectiveResolverInterface $directiveResolver,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
    ) {
    }

    public function getSchemaDefinition(): array
    {
        $schemaDefinition = $this->directiveResolver->getDirectiveSchemaDefinition($this->relationalTypeResolver);

        foreach (($schemaDefinition[SchemaDefinition::ARGS] ?? []) as $directiveArgName => &$directiveArgSchemaDefinition) {
            $directiveArgTypeResolver = $directiveArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
            $this->accessedTypeAndDirectiveResolvers[$directiveArgTypeResolver::class] = $directiveArgTypeResolver;
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($schemaDefinition[SchemaDefinition::ARGS][$directiveArgName]);
        }

        return $schemaDefinition;
    }
}

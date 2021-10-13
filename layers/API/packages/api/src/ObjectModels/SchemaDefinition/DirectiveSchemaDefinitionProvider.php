<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

class DirectiveSchemaDefinitionProvider extends AbstractSchemaDefinitionProvider implements SchemaDefinitionProviderInterface
{
    public function __construct(
        protected DirectiveResolverInterface $directiveResolver,
    ) {  
    }
    
    public function getSchemaDefinition(): array
    {
        return [
            // SchemaDefinition::NAME => $this->directiveResolver->getMaybeNamespacedDirectiveName(),
            // SchemaDefinition::NAMESPACED_NAME => $this->directiveResolver->getNamespacedDirectiveName(),
            // SchemaDefinition::ELEMENT_NAME => $this->directiveResolver->getDirectiveName(),
            SchemaDefinition::NAME => $this->directiveResolver->getDirectiveName(),
        ];
    }
}

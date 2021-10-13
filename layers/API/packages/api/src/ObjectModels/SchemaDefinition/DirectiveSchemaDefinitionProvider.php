<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

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
        return $this->directiveResolver->getDirectiveSchemaDefinition($this->relationalTypeResolver);
    }
}

<?php

declare(strict_types=1);

namespace PoP\Engine\Schema;

use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinitionService as ComponentModelSchemaDefinitionService;

class SchemaDefinitionService extends ComponentModelSchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    public function getTypeResolverTypeSchemaKey(string $typeResolverClass): string
    {
        $relationalTypeResolver = $this->instanceManager->getInstance($typeResolverClass);
        return $this->getTypeSchemaKey($relationalTypeResolver);
    }

    public function getRootTypeSchemaKey(): string
    {
        $rootTypeResolverClass = $this->getRootTypeResolverClass();
        return $this->getTypeResolverTypeSchemaKey($rootTypeResolverClass);
    }

    public function getRootTypeResolverClass(): string
    {
        return RootObjectTypeResolver::class;
    }
}

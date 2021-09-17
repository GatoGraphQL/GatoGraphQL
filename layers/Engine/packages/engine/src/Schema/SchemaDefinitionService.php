<?php

declare(strict_types=1);

namespace PoP\Engine\Schema;

use PoP\ComponentModel\Schema\SchemaDefinitionService as ComponentModelSchemaDefinitionService;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;

class SchemaDefinitionService extends ComponentModelSchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    /**
     * Can't use autowiring or it produces a circular reference exception
     */
    protected ?RootObjectTypeResolver $rootObjectTypeResolver = null;

    public function getTypeResolverTypeSchemaKey(RelationalTypeResolverInterface $relationalTypeResolver): string
    {
        return $this->getTypeSchemaKey($relationalTypeResolver);
    }

    public function getRootTypeSchemaKey(): string
    {
        $rootTypeResolver = $this->getRootTypeResolver();
        return $this->getTypeResolverTypeSchemaKey($rootTypeResolver);
    }

    public function getRootTypeResolver(): ObjectTypeResolverInterface
    {
        if ($this->rootObjectTypeResolver === null) {
            $this->rootObjectTypeResolver = $this->instanceManager->getInstance(RootObjectTypeResolver::class);
        }
        return $this->rootObjectTypeResolver;
    }
}

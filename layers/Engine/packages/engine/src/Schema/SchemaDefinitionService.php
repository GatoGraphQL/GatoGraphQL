<?php

declare(strict_types=1);

namespace PoP\Engine\Schema;

use PoP\ComponentModel\Schema\SchemaDefinitionService as ComponentModelSchemaDefinitionService;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaDefinitionService extends ComponentModelSchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    protected RootObjectTypeResolver $rootObjectTypeResolver;

    #[Required]
    public function autowireEngineSchemaDefinitionService(RootObjectTypeResolver $rootObjectTypeResolver)
    {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
    }

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
        return $this->rootObjectTypeResolver;
    }
}

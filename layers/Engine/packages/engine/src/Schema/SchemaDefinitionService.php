<?php

declare(strict_types=1);

namespace PoP\Engine\Schema;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinitionService as ComponentModelSchemaDefinitionService;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class SchemaDefinitionService extends ComponentModelSchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    public function __construct(protected InstanceManagerInterface $instanceManager)
    {
    }

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
        return RootTypeResolver::class;
    }
}

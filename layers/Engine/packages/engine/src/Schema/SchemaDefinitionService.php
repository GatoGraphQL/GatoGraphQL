<?php

declare(strict_types=1);

namespace PoP\Engine\Schema;

use PoP\ComponentModel\Schema\SchemaDefinitionService as UpstreamSchemaDefinitionService;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class SchemaDefinitionService extends UpstreamSchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    protected RootObjectTypeResolver $rootObjectTypeResolver;

    #[Required]
    final public function autowireEngineSchemaDefinitionService(RootObjectTypeResolver $rootObjectTypeResolver): void
    {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
    }

    public function getRootTypeSchemaKey(): string
    {
        $rootTypeResolver = $this->getRootTypeResolver();
        return $this->getTypeSchemaKey($rootTypeResolver);
    }

    public function getRootTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->rootObjectTypeResolver;
    }
}

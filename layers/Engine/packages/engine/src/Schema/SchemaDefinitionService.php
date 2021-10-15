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

    public function getRootObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->rootObjectTypeResolver;
    }
}

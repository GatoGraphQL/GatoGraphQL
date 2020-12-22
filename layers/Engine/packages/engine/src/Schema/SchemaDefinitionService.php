<?php

declare(strict_types=1);

namespace PoP\Engine\Schema;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Schema\SchemaDefinitionService as ComponentModelSchemaDefinitionService;

class SchemaDefinitionService extends ComponentModelSchemaDefinitionService implements SchemaDefinitionServiceInterface
{
    public function getRootTypeSchemaKey(): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $rootTypeResolverClass = $this->getRootTypeResolverClass();
        $rootTypeResolver = $instanceManager->getInstance($rootTypeResolverClass);
        return $this->getTypeSchemaKey($rootTypeResolver);
    }

    public function getRootTypeResolverClass(): string
    {
        return RootTypeResolver::class;
    }
}

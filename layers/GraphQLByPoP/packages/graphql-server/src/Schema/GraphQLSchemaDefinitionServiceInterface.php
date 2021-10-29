<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\API\Schema\SchemaDefinitionServiceInterface;

interface GraphQLSchemaDefinitionServiceInterface extends SchemaDefinitionServiceInterface
{
    public function getSchemaQueryRootObjectTypeResolver(): ObjectTypeResolverInterface;
    public function getSchemaMutationRootObjectTypeResolver(): ?ObjectTypeResolverInterface;
    public function getSchemaSubscriptionRootTypeResolver(): ?ObjectTypeResolverInterface;
}

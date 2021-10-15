<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\API\Schema\SchemaDefinitionServiceInterface;

interface GraphQLSchemaDefinitionServiceInterface extends SchemaDefinitionServiceInterface
{
    public function getQueryRootTypeResolver(): ObjectTypeResolverInterface;
    public function getMutationRootTypeResolver(): ?ObjectTypeResolverInterface;
    public function getSubscriptionRootTypeResolver(): ?ObjectTypeResolverInterface;
}

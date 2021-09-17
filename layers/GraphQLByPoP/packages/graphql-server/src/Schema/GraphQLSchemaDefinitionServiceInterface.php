<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Schema\SchemaDefinitionServiceInterface;

interface GraphQLSchemaDefinitionServiceInterface extends SchemaDefinitionServiceInterface
{
    public function getQueryRootTypeSchemaKey(): string;
    public function getQueryRootTypeResolver(): ObjectTypeResolverInterface;
    public function getMutationRootTypeSchemaKey(): ?string;
    public function getMutationRootTypeResolver(): ?ObjectTypeResolverInterface;
    public function getSubscriptionRootTypeSchemaKey(): ?string;
    public function getSubscriptionRootTypeResolver(): ?ObjectTypeResolverInterface;
}

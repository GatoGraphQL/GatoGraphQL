<?php

declare(strict_types=1);

namespace PoP\Engine\Schema;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface as ComponentModelSchemaDefinitionServiceInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface SchemaDefinitionServiceInterface extends ComponentModelSchemaDefinitionServiceInterface
{
    public function getTypeResolverTypeSchemaKey(RelationalTypeResolverInterface $relationalTypeResolver): string;
    public function getRootTypeSchemaKey(): string;
    public function getRootTypeResolver(): ObjectTypeResolverInterface;
}

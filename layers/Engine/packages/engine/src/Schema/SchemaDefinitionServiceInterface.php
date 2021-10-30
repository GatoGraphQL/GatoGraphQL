<?php

declare(strict_types=1);

namespace PoP\Engine\Schema;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface as UpstreamSchemaDefinitionServiceInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

interface SchemaDefinitionServiceInterface extends UpstreamSchemaDefinitionServiceInterface
{
    public function getSchemaRootObjectTypeResolver(): ObjectTypeResolverInterface;
}

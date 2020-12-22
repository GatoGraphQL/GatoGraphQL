<?php

declare(strict_types=1);

namespace PoP\Engine\Schema;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface as ComponentModelSchemaDefinitionServiceInterface;

interface SchemaDefinitionServiceInterface extends ComponentModelSchemaDefinitionServiceInterface
{
    public function getRootTypeSchemaKey(): string;
    public function getRootTypeResolverClass(): string;
}

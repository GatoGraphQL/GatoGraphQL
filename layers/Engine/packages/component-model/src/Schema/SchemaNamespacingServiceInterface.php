<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

interface SchemaNamespacingServiceInterface
{
    public function getSchemaNamespace(string $class): string;
    public function getSchemaNamespacedName(string $schemaNamespace, string $name): string;
}

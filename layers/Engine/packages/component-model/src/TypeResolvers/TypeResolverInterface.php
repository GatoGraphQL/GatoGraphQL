<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

interface TypeResolverInterface
{
    public function getTypeName(): string;
    public function getNamespace(): string;
    public function getNamespacedTypeName(): string;
    public function getMaybeNamespacedTypeName(): string;
    public function getTypeOutputName(): string;
    public function getSchemaTypeDescription(): ?string;
    public function getSchemaDefinition(array $stackMessages, array &$generalMessages, array $options = []): array;
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

interface TypeResolverInterface
{
    public function getTypeName(): string;
    public function getNamespace(): string;
    public function getNamespacedTypeName(): string;
    public function getMaybeNamespacedTypeName(): string;
    public function getTypeOutputKey(): string;
    public function getTypeDescription(): ?string;
}

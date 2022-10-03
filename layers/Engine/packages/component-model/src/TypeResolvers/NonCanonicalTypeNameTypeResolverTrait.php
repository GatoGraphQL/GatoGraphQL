<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;

/**
 * Trait to undo the effects from "CanonicalTypeNameTypeResolverTrait"
 * when extending a class that implements it.
 */
trait NonCanonicalTypeNameTypeResolverTrait
{
    public function getNamespace(): string
    {
        return $this->getSchemaNamespacingService()->getSchemaNamespace($this->getClassToNamespace());
    }

    abstract protected function getSchemaNamespacingService(): SchemaNamespacingServiceInterface;
    abstract protected function getClassToNamespace(): string;
}

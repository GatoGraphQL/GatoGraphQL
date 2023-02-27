<?php

declare(strict_types=1);

namespace PoP\AccessControl\Registries;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;

interface AccessControlValidationDirectiveResolverRegistryInterface
{
    public function addAccessControlValidationDirectiveResolver(FieldDirectiveResolverInterface $fieldDirectiveResolver): void;
    /**
     * @return array<string,FieldDirectiveResolverInterface>
     */
    public function getAccessControlValidationDirectiveResolvers(): array;
}

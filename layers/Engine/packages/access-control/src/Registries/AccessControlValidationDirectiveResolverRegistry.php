<?php

declare(strict_types=1);

namespace PoP\AccessControl\Registries;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;

class AccessControlValidationDirectiveResolverRegistry implements AccessControlValidationDirectiveResolverRegistryInterface
{
    /**
     * @var array<string,FieldDirectiveResolverInterface>
     */
    protected array $accessControlValidationDirectiveResolvers = [];

    public function addAccessControlValidationDirectiveResolver(FieldDirectiveResolverInterface $accessControlValidationDirectiveResolver): void
    {
        $this->accessControlValidationDirectiveResolvers[$accessControlValidationDirectiveResolver->getDirectiveName()] = $accessControlValidationDirectiveResolver;
    }

    /**
     * @return array<string,FieldDirectiveResolverInterface>
     */
    public function getAccessControlValidationDirectiveResolvers(): array
    {
        return $this->accessControlValidationDirectiveResolvers;
    }
}

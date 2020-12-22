<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

class DirectiveRegistry implements DirectiveRegistryInterface
{
    /**
     * @var string[]
     */
    protected array $directiveResolverClasses = [];

    public function addDirectiveResolverClass(string $directiveResolverClass): void
    {
        $this->directiveResolverClasses[] = $directiveResolverClass;
    }
    public function getDirectiveResolverClasses(): array
    {
        return $this->directiveResolverClasses;
    }
}

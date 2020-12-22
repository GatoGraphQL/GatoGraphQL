<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers;

trait ReservedNameTypeResolverTrait
{
    /**
     * Types with reserved names can keep their name, so they don't need be namespaced
     */
    public function getNamespace(): string
    {
        return '';
    }
}

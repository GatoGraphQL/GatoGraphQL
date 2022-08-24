<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommonsWP\Overrides\TypeResolvers;

trait OverridingTypeResolverTrait
{
    protected function getClassToNamespace(): string
    {
        /** @var string */
        return get_parent_class();
    }
}

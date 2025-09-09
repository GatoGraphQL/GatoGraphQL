<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\Overrides\TypeResolvers;

trait OverridingTypeResolverTrait
{
    protected function getClassToNamespace(): string
    {
        return get_parent_class(get_called_class());
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\Overrides\TypeResolvers;

use PoP\Root\Exception\ShouldNotHappenException;

trait OverridingTypeResolverTrait
{
    protected function getClassToNamespace(): string
    {
        $parentClass = get_parent_class(get_called_class());
        if ($parentClass === false) {
            throw new ShouldNotHappenException('Could not get parent class');
        }
        return $parentClass;
    }
}

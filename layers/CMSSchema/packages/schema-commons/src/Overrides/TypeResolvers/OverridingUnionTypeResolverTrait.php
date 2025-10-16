<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\Overrides\TypeResolvers;

trait OverridingUnionTypeResolverTrait
{
    use OverridingTypeResolverTrait;
    use SingleCallUnionTypeResolverTrait;
}

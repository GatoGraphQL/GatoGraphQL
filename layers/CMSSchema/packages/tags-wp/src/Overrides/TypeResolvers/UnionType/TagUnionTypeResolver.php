<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\Overrides\TypeResolvers\UnionType;

use PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver as UpstreamTagUnionTypeResolver;
use PoPCMSSchema\SchemaCommons\Overrides\TypeResolvers\OverridingUnionTypeResolverTrait;

class TagUnionTypeResolver extends UpstreamTagUnionTypeResolver
{
    use OverridingUnionTypeResolverTrait;
}

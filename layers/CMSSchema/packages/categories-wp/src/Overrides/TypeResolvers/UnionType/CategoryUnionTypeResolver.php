<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\Overrides\TypeResolvers\UnionType;

use PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver as UpstreamCategoryUnionTypeResolver;
use PoPCMSSchema\SchemaCommons\Overrides\TypeResolvers\OverridingUnionTypeResolverTrait;

class CategoryUnionTypeResolver extends UpstreamCategoryUnionTypeResolver
{
    use OverridingUnionTypeResolverTrait;
}

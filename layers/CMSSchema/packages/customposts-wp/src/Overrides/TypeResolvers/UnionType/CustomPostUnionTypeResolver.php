<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostsWP\Overrides\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver as UpstreamCustomPostUnionTypeResolver;
use PoPCMSSchema\SchemaCommons\Overrides\TypeResolvers\OverridingTypeResolverTrait;

class CustomPostUnionTypeResolver extends UpstreamCustomPostUnionTypeResolver
{
    use OverridingTypeResolverTrait;
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostsFilterInputObjectTypeResolverInterface;

abstract class AbstractMyCustomPostsFilterInputObjectTypeResolver extends AbstractCustomPostsFilterInputObjectTypeResolver implements CustomPostsFilterInputObjectTypeResolverInterface
{
    protected function treatCustomPostStatusAsSensitiveData(): bool
    {
        return false;
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }
}

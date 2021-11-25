<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType;

use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;

abstract class AbstractMyCustomPostsFilterInputObjectTypeResolver extends AbstractCustomPostsFilterInputObjectTypeResolver
{
    protected function treatCustomPostStatusAsAdminData(): bool
    {
        return false;
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }
}

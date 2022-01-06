<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\TypeResolvers\InputObjectType;

use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostsFilterInputObjectTypeResolverInterface;

class UserCustomPostsFilterInputObjectTypeResolver extends AbstractCustomPostsFilterInputObjectTypeResolver implements CustomPostsFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'UserCustomPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter the user\'s custom posts', 'users');
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }
}

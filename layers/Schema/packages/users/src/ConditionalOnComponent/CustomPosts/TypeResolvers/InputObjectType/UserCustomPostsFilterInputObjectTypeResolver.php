<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\TypeResolvers\InputObjectType;

use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;

class UserCustomPostsFilterInputObjectTypeResolver extends AbstractCustomPostsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'UserCustomPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to filter the user\'s custom posts', 'users');
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }
}

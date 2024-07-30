<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class RootMyCustomPostsFilterInputObjectTypeResolver extends AbstractMyCustomPostsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMyCustomPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter the logged-in user\'s categorys', 'category-mutations');
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }
}

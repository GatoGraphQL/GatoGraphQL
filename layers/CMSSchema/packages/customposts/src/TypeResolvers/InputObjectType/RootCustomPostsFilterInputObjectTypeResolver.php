<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType;

class RootCustomPostsFilterInputObjectTypeResolver extends AbstractWithParentCustomPostsFilterInputObjectTypeResolver implements CustomPostsFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootCustomPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter custom posts', 'gatographql');
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }

    protected function addParentInputFields(): bool
    {
        return true;
    }
}

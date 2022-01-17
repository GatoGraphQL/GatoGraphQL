<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class RootMyCustomPostsFilterInputObjectTypeResolver extends AbstractMyCustomPostsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMyCustomPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter the logged-in user\'s custom posts', 'custompost-mutations');
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }
}

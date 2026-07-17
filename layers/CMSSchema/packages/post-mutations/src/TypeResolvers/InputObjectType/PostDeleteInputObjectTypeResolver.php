<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractDeleteCustomPostInputObjectTypeResolver;

class PostDeleteInputObjectTypeResolver extends AbstractDeleteCustomPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostDeleteInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a post', 'gatographql');
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}

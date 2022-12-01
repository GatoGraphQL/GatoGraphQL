<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType;

class RootSetCategoriesOnPostMutationPayloadObjectTypeResolver extends AbstractPostCategoriesMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetCategoriesOnPostMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of setting categories on a post', 'postcategory-mutations');
    }
}

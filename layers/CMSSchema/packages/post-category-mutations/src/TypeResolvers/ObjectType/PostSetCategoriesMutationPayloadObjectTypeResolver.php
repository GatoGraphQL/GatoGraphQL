<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType;

class PostSetCategoriesMutationPayloadObjectTypeResolver extends AbstractPostCategoriesMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostSetCategoriesMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of setting categories on a post (using nested mutations)', 'postcategory-mutations');
    }
}

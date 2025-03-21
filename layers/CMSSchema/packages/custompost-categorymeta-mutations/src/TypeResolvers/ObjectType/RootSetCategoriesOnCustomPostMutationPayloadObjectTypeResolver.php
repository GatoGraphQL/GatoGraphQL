<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType;

class RootSetCategoriesOnCustomPostMutationPayloadObjectTypeResolver extends AbstractGenericCategoriesMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetCategoriesOnCustomPostMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of setting categories on a custom post', 'postcategory-mutations');
    }
}

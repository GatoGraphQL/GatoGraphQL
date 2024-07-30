<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType;

class RootUpdateGenericCategoryMutationPayloadObjectTypeResolver extends AbstractGenericCategoryMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateCategoryMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update mutation on a category', 'category-mutations');
    }
}

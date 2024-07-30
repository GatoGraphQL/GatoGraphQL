<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType;

class RootCreateGenericCategoryMutationPayloadObjectTypeResolver extends AbstractGenericCategoryMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreateCategoryMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of creating a category', 'category-mutations');
    }
}

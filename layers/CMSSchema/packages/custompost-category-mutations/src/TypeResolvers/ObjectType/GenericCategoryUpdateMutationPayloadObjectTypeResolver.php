<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType;

class GenericCategoryUpdateMutationPayloadObjectTypeResolver extends AbstractGenericCategoryMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCategoryUpdateMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update nested mutation on a category', 'category-mutations');
    }
}

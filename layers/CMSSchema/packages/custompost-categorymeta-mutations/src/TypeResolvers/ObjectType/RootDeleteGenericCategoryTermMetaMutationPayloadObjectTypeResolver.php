<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType;

class RootDeleteGenericCategoryTermMetaMutationPayloadObjectTypeResolver extends AbstractGenericCategoryMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeleteGenericCategoryTermMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete mutation on a category term', 'category-mutations');
    }
}

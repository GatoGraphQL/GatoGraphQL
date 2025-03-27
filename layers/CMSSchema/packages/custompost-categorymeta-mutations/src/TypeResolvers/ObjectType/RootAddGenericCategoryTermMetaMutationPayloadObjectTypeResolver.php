<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType;

class RootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver extends AbstractGenericCategoryMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootAddGenericCategoryTermMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of adding meta to a category term', 'category-mutations');
    }
}

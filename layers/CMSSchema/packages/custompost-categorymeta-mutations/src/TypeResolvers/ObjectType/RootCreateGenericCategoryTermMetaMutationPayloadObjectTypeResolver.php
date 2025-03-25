<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType;

class RootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver extends AbstractGenericCategoryMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreateGenericCategoryTermMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of creating a category term', 'category-mutations');
    }
}

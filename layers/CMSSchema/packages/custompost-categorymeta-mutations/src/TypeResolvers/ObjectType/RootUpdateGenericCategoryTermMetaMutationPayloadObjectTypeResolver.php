<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType;

class RootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver extends AbstractGenericCategoryMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateGenericCategoryTermMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update meta mutation on a category term', 'category-mutations');
    }
}

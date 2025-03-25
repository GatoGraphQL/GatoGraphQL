<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType;

class RootSetMetaOnCategoryMutationPayloadObjectTypeResolver extends AbstractGenericCategoriesMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetMetaOnCategoryMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of setting categories on a custom post', 'postcategory-mutations');
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType;

class GenericCustomPostSetCategoriesMutationPayloadObjectTypeResolver extends AbstractGenericCategoriesMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCustomPostSetCategoriesMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of setting categories on a custom post (using nested mutations)', 'postcategory-mutations');
    }
}

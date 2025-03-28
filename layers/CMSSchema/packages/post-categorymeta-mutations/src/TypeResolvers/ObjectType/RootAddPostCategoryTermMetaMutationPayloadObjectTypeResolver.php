<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType;

class RootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver extends AbstractPostCategoryMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootAddPostCategoryTermMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of adding meta to a posts\'s category term', 'category-mutations');
    }
}

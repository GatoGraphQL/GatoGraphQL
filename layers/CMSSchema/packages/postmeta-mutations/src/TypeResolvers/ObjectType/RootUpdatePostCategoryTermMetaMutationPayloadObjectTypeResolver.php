<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType;

class RootUpdatePostCategoryTermMetaMutationPayloadObjectTypeResolver extends AbstractPostMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdatePostCategoryTermMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update meta mutation on a post\'s category term', 'category-mutations');
    }
}

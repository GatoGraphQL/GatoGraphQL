<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\ObjectType;

class RootDeletePageMutationPayloadObjectTypeResolver extends AbstractPageMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeletePageMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete mutation on a page', 'gatographql');
    }
}

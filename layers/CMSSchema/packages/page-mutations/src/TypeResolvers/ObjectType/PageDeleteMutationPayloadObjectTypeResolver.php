<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\ObjectType;

class PageDeleteMutationPayloadObjectTypeResolver extends AbstractPageMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PageDeleteMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete mutation on a page', 'gatographql');
    }
}

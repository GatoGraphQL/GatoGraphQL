<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\ObjectType;

class RootUpdatePageMutationPayloadObjectTypeResolver extends AbstractPageMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdatePageMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update mutation on a page', 'page-mutations');
    }
}

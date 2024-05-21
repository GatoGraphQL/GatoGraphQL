<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\ObjectType;

class RootCreatePageMutationPayloadObjectTypeResolver extends AbstractPageMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreatePageMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of creating a page', 'page-mutations');
    }
}

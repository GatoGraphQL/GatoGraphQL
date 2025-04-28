<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType;

class RootDeletePageMetaMutationPayloadObjectTypeResolver extends AbstractPageMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootDeletePageMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete meta mutation on a page', 'pagemeta-mutations');
    }
}

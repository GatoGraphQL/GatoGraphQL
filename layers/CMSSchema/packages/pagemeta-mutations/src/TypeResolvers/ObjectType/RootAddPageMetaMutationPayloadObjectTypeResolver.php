<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType;

class RootAddPageMetaMutationPayloadObjectTypeResolver extends AbstractPageMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootAddPageMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of adding meta to a pages\'s custom post', 'pagemeta-mutations');
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType;

class PageSetMetaMutationPayloadObjectTypeResolver extends AbstractPageMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PageSetMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a set meta nested mutation on a page', 'pagemeta-mutations');
    }
}

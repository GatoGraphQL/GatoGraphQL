<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType;

class PageDeleteMetaMutationPayloadObjectTypeResolver extends AbstractPageMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PageDeleteMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a delete meta nested mutation on a page', 'pagemeta-mutations');
    }
}

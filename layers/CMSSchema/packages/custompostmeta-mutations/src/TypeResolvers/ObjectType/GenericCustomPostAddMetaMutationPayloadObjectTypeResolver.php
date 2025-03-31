<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType;

class GenericCustomPostAddMetaMutationPayloadObjectTypeResolver extends AbstractGenericCustomPostMetaMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCustomPostAddMetaMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an add meta nested mutation on a custom post', 'custompost-mutations');
    }
}

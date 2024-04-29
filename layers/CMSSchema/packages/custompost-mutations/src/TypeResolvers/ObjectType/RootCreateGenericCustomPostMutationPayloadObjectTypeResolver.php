<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

class RootCreateGenericCustomPostMutationPayloadObjectTypeResolver extends AbstractGenericCustomPostMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreateCustomPostMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of creating a custom post', 'custompost-mutations');
    }
}

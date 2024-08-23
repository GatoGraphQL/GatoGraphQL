<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType;

class GenericCustomPostSetTagsMutationPayloadObjectTypeResolver extends AbstractGenericTagsMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCustomPostSetTagsMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of setting tags on a custom post (using nested mutations)', 'posttag-mutations');
    }
}

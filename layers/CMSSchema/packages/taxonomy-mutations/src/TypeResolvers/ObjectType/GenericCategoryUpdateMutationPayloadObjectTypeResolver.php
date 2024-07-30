<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType;

class GenericTaxonomyUpdateMutationPayloadObjectTypeResolver extends AbstractGenericTaxonomyMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericTaxonomyUpdateMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update nested mutation on a generic taxonomy', 'taxonomy-mutations');
    }
}

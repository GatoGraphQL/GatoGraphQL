<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType;

class RootUpdateGenericTaxonomyMutationPayloadObjectTypeResolver extends AbstractGenericTaxonomyMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateTaxonomyMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update mutation on a taxonomy', 'taxonomy-mutations');
    }
}

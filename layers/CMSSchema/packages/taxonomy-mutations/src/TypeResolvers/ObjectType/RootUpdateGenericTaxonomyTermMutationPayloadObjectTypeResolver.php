<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType;

class RootUpdateGenericTaxonomyTermMutationPayloadObjectTypeResolver extends AbstractGenericTaxonomyMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootUpdateTaxonomyTermMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing an update mutation on a taxonomy term', 'taxonomy-mutations');
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType;

class RootCreateGenericTaxonomyMutationPayloadObjectTypeResolver extends AbstractGenericTaxonomyMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreateTaxonomyMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of creating a taxonomy', 'taxonomy-mutations');
    }
}

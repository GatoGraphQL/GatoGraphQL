<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType;

class RootCreateGenericTaxonomyTermMutationPayloadObjectTypeResolver extends AbstractGenericTaxonomyMutationPayloadObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreateTaxonomyTermMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of creating a taxonomy term', 'taxonomy-mutations');
    }
}

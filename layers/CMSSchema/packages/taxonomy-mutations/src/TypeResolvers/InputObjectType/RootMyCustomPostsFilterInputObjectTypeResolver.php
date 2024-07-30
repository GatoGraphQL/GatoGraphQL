<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

class RootMyCustomPostsFilterInputObjectTypeResolver extends AbstractMyCustomPostsFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMyCustomPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter the logged-in user\'s taxonomys', 'taxonomy-mutations');
    }

    protected function addCustomPostInputFields(): bool
    {
        return true;
    }
}

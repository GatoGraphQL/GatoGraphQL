<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType;

class RootSetTagsOnPostInputObjectTypeResolver extends AbstractSetTagsOnPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetTagsOnPostInput';
    }

    protected function addTaxonomyInputField(): bool
    {
        return false;
    }

    protected function addCustomPostInputField(): bool
    {
        return true;
    }
}

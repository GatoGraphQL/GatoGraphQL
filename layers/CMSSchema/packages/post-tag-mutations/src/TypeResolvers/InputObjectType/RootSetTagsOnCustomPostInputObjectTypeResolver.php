<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType;

class RootSetTagsOnCustomPostInputObjectTypeResolver extends AbstractSetTagsOnPostFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetTagsOnCustomPostInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return true;
    }
}

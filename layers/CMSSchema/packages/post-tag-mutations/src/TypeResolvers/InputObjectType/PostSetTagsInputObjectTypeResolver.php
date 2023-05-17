<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType;

class PostSetTagsInputObjectTypeResolver extends AbstractSetTagsOnPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostSetTagsInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}

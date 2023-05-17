<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType;

class PostSetTagsFilterInputObjectTypeResolver extends AbstractSetTagsOnPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostSetTagsFilterInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}

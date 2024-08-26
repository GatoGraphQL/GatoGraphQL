<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType;

class RootSetTagsOnCustomPostInputObjectTypeResolver extends AbstractSetTagsOnGenericCustomPostInputObjectTypeResolver
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

<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType;

class GenericCustomPostSetTagsInputObjectTypeResolver extends AbstractSetTagsOnGenericCustomPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCustomPostSetTagsInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}

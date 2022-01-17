<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType;

class RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver extends AbstractSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootSetFeaturedImageOnCustomPostFilterInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return true;
    }
}

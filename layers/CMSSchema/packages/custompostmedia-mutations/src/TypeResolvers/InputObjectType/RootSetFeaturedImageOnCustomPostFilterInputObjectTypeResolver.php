<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType;

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

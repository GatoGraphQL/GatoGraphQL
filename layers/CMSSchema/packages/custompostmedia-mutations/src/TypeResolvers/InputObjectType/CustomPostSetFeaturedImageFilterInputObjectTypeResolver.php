<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType;

class CustomPostSetFeaturedImageFilterInputObjectTypeResolver extends AbstractSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostSetFeaturedImageFilterInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}

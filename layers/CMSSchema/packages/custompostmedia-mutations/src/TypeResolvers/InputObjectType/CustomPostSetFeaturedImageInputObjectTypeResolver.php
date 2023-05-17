<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType;

class CustomPostSetFeaturedImageInputObjectTypeResolver extends AbstractSetFeaturedImageOnCustomPostInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostSetFeaturedImageInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}

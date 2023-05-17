<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType;

class CustomPostSetFeaturedImageFilterInputObjectTypeResolver extends AbstractSetFeaturedImageOnCustomPostInputObjectTypeResolver
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

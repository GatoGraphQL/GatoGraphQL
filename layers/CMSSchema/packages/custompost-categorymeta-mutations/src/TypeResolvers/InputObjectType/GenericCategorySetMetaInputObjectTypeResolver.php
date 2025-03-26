<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

class GenericCategorySetMetaInputObjectTypeResolver extends AbstractSetMetaOnCategoryInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'GenericCategorySetMetaInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}

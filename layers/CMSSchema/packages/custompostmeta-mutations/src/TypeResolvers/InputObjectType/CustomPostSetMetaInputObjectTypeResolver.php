<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType;

class CustomPostSetMetaInputObjectTypeResolver extends AbstractSetCustomPostMetaInputObjectTypeResolver implements SetCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CustomPostSetMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}

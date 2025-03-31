<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

class CustomPostDeleteMetaInputObjectTypeResolver extends AbstractDeleteCustomPostMetaInputObjectTypeResolver implements DeleteCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CategoryDeleteMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}

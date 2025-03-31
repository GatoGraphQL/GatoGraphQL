<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType;

class CustomPostDeleteMetaInputObjectTypeResolver extends AbstractDeleteCustomPostMetaInputObjectTypeResolver implements DeleteCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CustomPostDeleteMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}

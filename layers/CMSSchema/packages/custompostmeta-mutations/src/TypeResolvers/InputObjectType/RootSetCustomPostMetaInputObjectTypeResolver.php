<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType;

class RootSetCustomPostMetaInputObjectTypeResolver extends AbstractSetCustomPostMetaInputObjectTypeResolver implements SetCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootSetCustomPostMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType;

class RootSetTagTermMetaInputObjectTypeResolver extends AbstractSetTagTermMetaInputObjectTypeResolver implements SetTagTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootSetTagMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType;

class RootUpdateTagTermMetaInputObjectTypeResolver extends AbstractUpdateTagTermMetaInputObjectTypeResolver implements UpdateTagTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateTagMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}

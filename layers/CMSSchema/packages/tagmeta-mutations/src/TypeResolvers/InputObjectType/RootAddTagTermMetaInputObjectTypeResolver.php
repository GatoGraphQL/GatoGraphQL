<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType;

class RootAddTagTermMetaInputObjectTypeResolver extends AbstractAddTagTermMetaInputObjectTypeResolver implements AddTagTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootAddTagMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}

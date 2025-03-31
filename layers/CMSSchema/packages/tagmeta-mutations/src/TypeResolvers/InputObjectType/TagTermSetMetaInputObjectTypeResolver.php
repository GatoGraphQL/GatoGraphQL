<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType;

class TagTermSetMetaInputObjectTypeResolver extends AbstractSetTagTermMetaInputObjectTypeResolver implements SetTagTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'TagSetMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}

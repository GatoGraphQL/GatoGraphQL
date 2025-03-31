<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType;

class TagTermUpdateMetaInputObjectTypeResolver extends AbstractUpdateTagTermMetaInputObjectTypeResolver implements UpdateTagTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'TagUpdateMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}

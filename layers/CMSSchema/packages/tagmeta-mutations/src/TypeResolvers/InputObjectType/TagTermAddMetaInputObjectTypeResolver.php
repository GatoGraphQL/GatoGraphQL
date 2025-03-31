<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType;

class TagTermAddMetaInputObjectTypeResolver extends AbstractAddTagTermMetaInputObjectTypeResolver implements AddTagTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'TagAddMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}

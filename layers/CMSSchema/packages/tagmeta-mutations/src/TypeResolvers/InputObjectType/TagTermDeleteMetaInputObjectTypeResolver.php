<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType;

class TagTermDeleteMetaInputObjectTypeResolver extends AbstractDeleteTagTermMetaInputObjectTypeResolver implements DeleteTagTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'TagDeleteMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}

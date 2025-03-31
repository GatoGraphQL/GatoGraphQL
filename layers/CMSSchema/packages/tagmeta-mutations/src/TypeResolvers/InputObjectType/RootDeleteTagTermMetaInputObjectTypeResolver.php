<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType;

class RootDeleteTagTermMetaInputObjectTypeResolver extends AbstractDeleteTagTermMetaInputObjectTypeResolver implements DeleteTagTermMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootDeleteTagMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}

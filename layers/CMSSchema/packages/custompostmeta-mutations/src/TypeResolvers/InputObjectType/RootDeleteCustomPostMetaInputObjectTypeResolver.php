<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType;

class RootDeleteCustomPostMetaInputObjectTypeResolver extends AbstractDeleteCustomPostMetaInputObjectTypeResolver implements DeleteCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootDeleteCustomPostMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType;

class RootUpdateCustomPostMetaInputObjectTypeResolver extends AbstractUpdateCustomPostMetaInputObjectTypeResolver implements UpdateCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateCustomPostMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}

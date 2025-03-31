<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType;

class RootAddCustomPostMetaInputObjectTypeResolver extends AbstractAddCustomPostMetaInputObjectTypeResolver implements AddCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootAddCustomPostMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return true;
    }
}

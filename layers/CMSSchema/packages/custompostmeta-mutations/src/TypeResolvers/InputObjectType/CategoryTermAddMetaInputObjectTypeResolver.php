<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

class CustomPostAddMetaInputObjectTypeResolver extends AbstractAddCustomPostMetaInputObjectTypeResolver implements AddCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CategoryAddMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}

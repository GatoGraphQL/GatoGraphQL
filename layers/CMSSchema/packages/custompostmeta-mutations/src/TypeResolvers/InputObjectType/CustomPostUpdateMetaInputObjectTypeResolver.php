<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType;

class CustomPostUpdateMetaInputObjectTypeResolver extends AbstractUpdateCustomPostMetaInputObjectTypeResolver implements UpdateCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CustomPostUpdateMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}

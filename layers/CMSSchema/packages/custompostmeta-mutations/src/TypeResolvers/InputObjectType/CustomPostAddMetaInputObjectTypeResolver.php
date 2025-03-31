<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\InputObjectType;

class CustomPostAddMetaInputObjectTypeResolver extends AbstractAddCustomPostMetaInputObjectTypeResolver implements AddCustomPostMetaInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CustomPostAddMetaInput';
    }

    protected function addIDInputField(): bool
    {
        return false;
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class RootUpdateCustomPostFilterInputObjectTypeResolver extends AbstractCreateOrUpdateCustomPostFilterInputObjectTypeResolver implements UpdateCustomPostFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateCustomPostFilterInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return true;
    }
}

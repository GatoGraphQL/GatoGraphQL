<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class CustomPostUpdateFilterInputObjectTypeResolver extends AbstractCreateOrUpdateCustomPostFilterInputObjectTypeResolver implements UpdateCustomPostFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CustomPostUpdateFilterInput';
    }

    protected function addCustomPostIDInputField(): bool
    {
        return false;
    }
}

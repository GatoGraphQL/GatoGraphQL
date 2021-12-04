<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class RootCreateCustomPostFilterInputObjectTypeResolver extends AbstractCreateOrUpdateCustomPostFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCreateCustomPostFilterInput';
    }

    protected function addCustomPostIDInputField(): bool
    {
        return false;
    }
}

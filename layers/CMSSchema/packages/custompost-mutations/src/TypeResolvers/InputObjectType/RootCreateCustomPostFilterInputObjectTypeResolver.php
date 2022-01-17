<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class RootCreateCustomPostFilterInputObjectTypeResolver extends AbstractCreateOrUpdateCustomPostFilterInputObjectTypeResolver implements CreateCustomPostFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootCreateCustomPostFilterInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class RootCreateCustomPostFilterInputObjectTypeResolver extends AbstractCreateOrUpdateCustomPostInputObjectTypeResolver implements CreateCustomPostFilterInputObjectTypeResolverInterface
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

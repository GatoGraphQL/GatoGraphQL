<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class RootUpdateGenericCustomPostInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCustomPostInputObjectTypeResolver implements UpdateCustomPostInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdateCustomPostInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return true;
    }
}

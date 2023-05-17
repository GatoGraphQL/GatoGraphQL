<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class RootUpdateCustomPostInputObjectTypeResolver extends AbstractCreateOrUpdateCustomPostInputObjectTypeResolver implements UpdateCustomPostInputObjectTypeResolverInterface
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

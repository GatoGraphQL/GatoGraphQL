<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class RootCreateCustomPostInputObjectTypeResolver extends AbstractCreateOrUpdateCustomPostInputObjectTypeResolver implements CreateCustomPostInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootCreateCustomPostInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}

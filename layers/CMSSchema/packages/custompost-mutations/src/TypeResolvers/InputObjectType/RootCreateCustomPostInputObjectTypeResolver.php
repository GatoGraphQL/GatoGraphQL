<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class RootCreateCustomPostInputObjectTypeResolver extends AbstractCreateOrUpdateCustomPostInputObjectTypeResolver implements CreateCustomPostInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootCreateCustomPostInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to create a custom post', 'gatographql');
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }

    protected function addCustomPostParentInputField(): bool
    {
        return true;
    }
}

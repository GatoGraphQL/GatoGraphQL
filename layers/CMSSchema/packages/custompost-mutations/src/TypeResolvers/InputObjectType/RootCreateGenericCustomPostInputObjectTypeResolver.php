<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class RootCreateGenericCustomPostInputObjectTypeResolver extends AbstractCreateOrUpdateGenericCustomPostInputObjectTypeResolver implements CreateCustomPostInputObjectTypeResolverInterface
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

    protected function isCustomPostTypeFieldMandatory(): bool
    {
        return true;
    }
}

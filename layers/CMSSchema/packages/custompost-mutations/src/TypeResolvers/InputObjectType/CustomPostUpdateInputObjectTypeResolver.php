<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class CustomPostUpdateInputObjectTypeResolver extends AbstractCreateOrUpdateCustomPostInputObjectTypeResolver implements UpdateCustomPostInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'CustomPostUpdateInput';
    }

    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class GenericCustomPostUpdateInputObjectTypeResolver extends AbstractCustomPostUpdateInputObjectTypeResolver implements UpdateGenericCustomPostInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'GenericCustomPostUpdateInput';
    }

    protected function addCustomPostParentInputField(): bool
    {
        return true;
    }
}

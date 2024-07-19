<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class GenericCustomPostUpdateInputObjectTypeResolver extends CustomPostUpdateInputObjectTypeResolver implements UpdateGenericCustomPostInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'GenericCustomPostUpdateInput';
    }
}

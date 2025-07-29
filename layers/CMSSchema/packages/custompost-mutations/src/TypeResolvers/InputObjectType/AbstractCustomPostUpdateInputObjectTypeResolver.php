<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

abstract class AbstractCustomPostUpdateInputObjectTypeResolver extends AbstractCreateOrUpdateCustomPostInputObjectTypeResolver implements UpdateCustomPostInputObjectTypeResolverInterface
{
    protected function addCustomPostInputField(): bool
    {
        return false;
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType;

trait RootDeleteCategoryTermInputObjectTypeResolverTrait
{
    protected function addIDInputField(): bool
    {
        return true;
    }
}

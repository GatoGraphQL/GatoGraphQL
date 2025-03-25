<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

trait RootDeleteCategoryTermMetaInputObjectTypeResolverTrait
{
    protected function addIDInputField(): bool
    {
        return true;
    }
}

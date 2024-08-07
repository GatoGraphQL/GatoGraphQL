<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

trait RootDeleteTagTermInputObjectTypeResolverTrait
{
    protected function addTaxonomyInputField(): bool
    {
        return true;
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

class RootDeleteTagTermInputObjectTypeResolver extends AbstractDeleteTagTermInputObjectTypeResolver implements DeleteTagTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootDeleteTagInput';
    }

    protected function addTaxonomyInputField(): bool
    {
        return true;
    }
}

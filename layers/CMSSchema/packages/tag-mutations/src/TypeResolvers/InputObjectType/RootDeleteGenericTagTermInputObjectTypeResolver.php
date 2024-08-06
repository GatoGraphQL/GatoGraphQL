<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType;

class RootDeleteGenericTagTermInputObjectTypeResolver extends AbstractDeleteGenericTagTermInputObjectTypeResolver implements DeleteTagTermInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootDeleteGenericTagInput';
    }

    protected function addTaxonomyInputField(): bool
    {
        return true;
    }
}
